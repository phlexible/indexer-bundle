<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Storage\Operation;

use Phlexible\Bundle\IndexerBundle\Event\DocumentEvent;
use Phlexible\Bundle\IndexerBundle\IndexerEvents;
use Phlexible\Bundle\IndexerBundle\Model\StorageInterface;
use Phlexible\Bundle\IndexerBundle\Storage\Commitable;
use Phlexible\Bundle\IndexerBundle\Storage\Flushable;
use Phlexible\Bundle\IndexerBundle\Storage\Optimizable;
use Phlexible\Bundle\IndexerBundle\Storage\Rollbackable;
use Phlexible\Bundle\QueueBundle\Entity\Job;
use Phlexible\Bundle\QueueBundle\Model\JobManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Operater
 * Executes operations on storage.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class Operator
{
    /**
     * @var JobManagerInterface
     */
    private $jobManager;

    /**
      * @var EventDispatcherInterface
      */
    private $eventDispatcher;

    /**
     * @param JobManagerInterface      $jobManager
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(JobManagerInterface $jobManager, EventDispatcherInterface $dispatcher)
    {
        $this->jobManager = $jobManager;
        $this->eventDispatcher = $dispatcher;
    }

    /**
     * @return Operations
     */
    public function createOperations()
    {
        return new Operations();
    }

    /**
     * Queue operations.
     *
     * @param Operations $operations
     *
     * @return bool
     */
    public function queue(Operations $operations)
    {
        foreach ($operations->getOperations() as $operation) {
            if ($operation instanceof AddDocumentOperation) {
                $identifier = $operation->getDocument()->getIdentifier();
                $job = new Job('indexer:add', array($identifier));
            } elseif ($operation instanceof AddIdentifierOperation) {
                $identifier = $operation->getIdentifier();
                $job = new Job('indexer:add', array($identifier));
            } elseif ($operation instanceof UpdateDocumentOperation) {
                $identifier = $operation->getDocument()->getIdentifier();
                $job = new Job('indexer:add', array('--update', $identifier));
            } elseif ($operation instanceof UpdateIdentifierOperation) {
                $identifier = $operation->getIdentifier();
                $job = new Job('indexer:add', array('--update', $identifier));
            } elseif ($operation instanceof DeleteDocumentOperation) {
                $identifier = $operation->getDocument()->getIdentifier();
                $job = new Job('indexer:delete', array('--identifier', $identifier));
            } elseif ($operation instanceof DeleteIdentifierOperation) {
                $job = new Job('indexer:delete', array('--identifier', $operation->getIdentifier()));
            } elseif ($operation instanceof DeleteTypeOperation) {
                $job = new Job('indexer:delete', array('--type', $operation->getType()));
            } elseif ($operation instanceof DeleteAllOperation) {
                $job = new Job('indexer:delete', array('--all'));
            } elseif ($operation instanceof FlushOperation) {
                $job = new Job('indexer:index', array('--flush'));
            } elseif ($operation instanceof OptimizeOperation) {
                $job = new Job('indexer:index', array('--optimize'));
            } elseif ($operation instanceof CommitOperation) {
                $job = new Job('indexer:index', array('--commit'));
            } elseif ($operation instanceof RollbackOperation) {
                $job = new Job('indexer:index', array('--rollback'));
            } else {
                continue;
            }

            $this->jobManager->addJob($job);
        }

        return true;
    }

    /**
     * Execute operations.
     *
     * @param StorageInterface $storage
     * @param Operations       $operations
     *
     * @return bool
     */
    public function execute(StorageInterface $storage, Operations $operations)
    {
        foreach ($operations->getOperations() as $operation) {
            if ($operation instanceof AddDocumentOperation) {
                $document = $operation->getDocument();
                $event = new DocumentEvent($document);
                if ($this->eventDispatcher->dispatch(IndexerEvents::BEFORE_STORAGE_ADD_DOCUMENT, $event)
                    ->isPropagationStopped()
                ) {
                    continue;
                }

                $storage->addDocument($document);

                $event = new DocumentEvent($document);
                $this->eventDispatcher->dispatch(IndexerEvents::STORAGE_ADD_DOCUMENT, $event);
            } elseif ($operation instanceof AddIdentifierOperation) {
                throw new \InvalidArgumentException("Add identifier command not supported by run().");
            } elseif ($operation instanceof UpdateDocumentOperation) {
                $document = $operation->getDocument();
                $event = new DocumentEvent($document);
                if ($this->eventDispatcher->dispatch(IndexerEvents::BEFORE_STORAGE_UPDATE_DOCUMENT, $event)->isPropagationStopped()) {
                    continue;
                }

                $storage->updateDocument($document);

                $event = new DocumentEvent($document);
                $this->eventDispatcher->dispatch(IndexerEvents::STORAGE_UPDATE_DOCUMENT, $event);
            } elseif ($operation instanceof UpdateIdentifierOperation) {
                throw new \InvalidArgumentException("Update identifier command not supported by run().");
            } elseif ($operation instanceof DeleteDocumentOperation) {
                $storage->deleteDocument($operation->getDocument());
            } elseif ($operation instanceof DeleteIdentifierOperation) {
                $storage->delete($operation->getIdentifier());
            } elseif ($operation instanceof DeleteTypeOperation) {
                $storage->deleteType($operation->getType());
            } elseif ($operation instanceof DeleteAllOperation) {
                $storage->deleteAll();
            } elseif ($operation instanceof FlushOperation) {
                if ($storage instanceof Flushable) {
                    $storage->flush();
                }
            } elseif ($operation instanceof OptimizeOperation) {
                if ($storage instanceof Optimizable) {
                    $storage->optimize();
                }
            } elseif ($operation instanceof CommitOperation) {
                if ($storage instanceof Commitable) {
                    $storage->commit();
                }
            } elseif ($operation instanceof RollbackOperation) {
                if ($storage instanceof Rollbackable) {
                    $storage->rollback();
                }
            }
        }

        return true;
    }
}
