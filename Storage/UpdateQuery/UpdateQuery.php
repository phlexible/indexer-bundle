<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery;

use Phlexible\Bundle\IndexerBundle\Event\DocumentEvent;
use Phlexible\Bundle\IndexerBundle\IndexerEvents;
use Phlexible\Bundle\IndexerBundle\Storage\Commitable;
use Phlexible\Bundle\IndexerBundle\Storage\Flushable;
use Phlexible\Bundle\IndexerBundle\Storage\Optimizable;
use Phlexible\Bundle\IndexerBundle\Storage\Rollbackable;
use Phlexible\Bundle\IndexerBundle\Storage\StorageInterface;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\AddDocumentCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\CommandCollection;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\CommandInterface;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\CommitCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\DeleteAllCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\DeleteCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\DeleteDocumentCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\DeleteTypeCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\FlushCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\OptimizeCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\RollbackCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\UpdateDocumentCommand;
use Phlexible\Bundle\QueueBundle\Entity\Job;
use Phlexible\Bundle\QueueBundle\Model\JobManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Update query
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class UpdateQuery
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
     * @return CommandCollection
     */
    public function createCommands()
    {
        return new CommandCollection();
    }

    /**
     * @param CommandCollection $commands
     *
     * @return bool
     */
    public function queue(CommandCollection $commands)
    {
        foreach ($commands->all() as $command) {
            if ($command instanceof AddDocumentCommand) {
                $identifier = $command->getDocument()->getIdentifier();
                $job = new Job('indexer:add', array($identifier));
            } elseif ($command instanceof UpdateDocumentCommand) {
                $identifier = $command->getDocument()->getIdentifier();
                $job = new Job('indexer:add', array('--update', $identifier));
            } elseif ($command instanceof DeleteDocumentCommand) {
                $identifier = $command->getDocument()->getIdentifier();
                $job = new Job('indexer:delete', array('--identifier', $identifier));
            } elseif ($command instanceof DeleteCommand) {
                $job = new Job('indexer:delete', array('--identifier', $command->getIdentifier()));
            } elseif ($command instanceof DeleteTypeCommand) {
                $job = new Job('indexer:delete', array('--type', $command->getType()));
            } elseif ($command instanceof DeleteAllCommand) {
                $job = new Job('indexer:delete', array('--all'));
            } elseif ($command instanceof FlushCommand) {
                $job = new Job('indexer:index', array('--flush'));
            } elseif ($command instanceof OptimizeCommand) {
                $job = new Job('indexer:index', array('--optimize'));
            } elseif ($command instanceof CommitCommand) {
                $job = new Job('indexer:index', array('--commit'));
            } elseif ($command instanceof RollbackCommand) {
                $job = new Job('indexer:index', array('--rollback'));
            } else {
                continue;
            }

            $this->jobManager->addJob($job);
        }

        return true;
    }

    /**
     * @param StorageInterface  $storage
     * @param CommandCollection $commands
     *
     * @return bool
     */
    public function run(StorageInterface $storage, CommandCollection $commands)
    {
        foreach ($commands->all() as $command) {
            if ($command instanceof AddDocumentCommand) {
                $document = $command->getDocument();
                $event = new DocumentEvent($document);
                if ($this->eventDispatcher->dispatch(IndexerEvents::BEFORE_STORAGE_ADD_DOCUMENT, $event)->isPropagationStopped()) {
                    continue;
                }

                $storage->addDocument($command->getDocument());

                $event = new DocumentEvent($document);
                $this->eventDispatcher->dispatch(IndexerEvents::STORAGE_ADD_DOCUMENT, $event);
            } elseif ($command instanceof UpdateDocumentCommand) {
                $document = $command->getDocument();
                $event = new DocumentEvent($document);
                if ($this->eventDispatcher->dispatch(IndexerEvents::BEFORE_STORAGE_UPDATE_DOCUMENT, $event)->isPropagationStopped()) {
                    continue;
                }

                $storage->updateDocument($document);

                $event = new DocumentEvent($document);
                $this->eventDispatcher->dispatch(IndexerEvents::STORAGE_UPDATE_DOCUMENT, $event);
            } elseif ($command instanceof DeleteDocumentCommand) {
                $storage->deleteDocument($command->getDocument());
            } elseif ($command instanceof DeleteCommand) {
                $storage->delete($command->getIdentifier());
            } elseif ($command instanceof DeleteTypeCommand) {
                $storage->deleteType($command->getType());
            } elseif ($command instanceof DeleteAllCommand) {
                $storage->deleteAll();
            } elseif ($command instanceof FlushCommand) {
                if ($storage instanceof Flushable) {
                    $storage->flush();
                }
            } elseif ($command instanceof OptimizeCommand) {
                if ($storage instanceof Optimizable) {
                    $storage->optimize();
                }
            } elseif ($command instanceof CommitCommand) {
                if ($storage instanceof Commitable) {
                    $storage->commit();
                }
            } elseif ($command instanceof RollbackCommand) {
                if ($storage instanceof Rollbackable) {
                    $storage->rollback();
                }
            }
        }

        return true;
    }
}
