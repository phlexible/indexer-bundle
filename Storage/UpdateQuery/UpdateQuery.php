<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery;

use Phlexible\Bundle\IndexerBundle\Document\DocumentInterface;
use Phlexible\Bundle\IndexerBundle\Event\DocumentEvent;
use Phlexible\Bundle\IndexerBundle\IndexerEvents;
use Phlexible\Bundle\IndexerBundle\Storage\Commitable;
use Phlexible\Bundle\IndexerBundle\Storage\Flushable;
use Phlexible\Bundle\IndexerBundle\Storage\Optimizable;
use Phlexible\Bundle\IndexerBundle\Storage\Rollbackable;
use Phlexible\Bundle\IndexerBundle\Storage\StorageInterface;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\AddCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\AddDocumentCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\CommandInterface;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\CommitCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\DeleteAllCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\DeleteCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\DeleteDocumentCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\DeleteTypeCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\FlushCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\OptimizeCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\RollbackCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\UpdateCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\UpdateDocumentCommand;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Update
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class UpdateQuery
{
    /**
      * @var EventDispatcherInterface
      */
    private $dispatcher;

    /**
     * @var CommandInterface[]
     */
    private $commands = array();

    /**
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param StorageInterface $storage
     */
    public function execute(StorageInterface $storage)
    {
        foreach ($this->commands as $command) {
            if ($command instanceof AddDocumentCommand) {
                $document = $command->getDocument();
                $event = new DocumentEvent($document);
                if ($this->dispatcher->dispatch(IndexerEvents::BEFORE_STORAGE_ADD_DOCUMENT, $event)->isPropagationStopped()) {
                    continue;
                }

                $storage->addDocument($command->getDocument());

                $event = new DocumentEvent($document);
                $this->dispatcher->dispatch(IndexerEvents::STORAGE_ADD_DOCUMENT, $event);
            } elseif ($command instanceof UpdateDocumentCommand) {
                $document = $command->getDocument();
                $event = new DocumentEvent($document);
                if ($this->dispatcher->dispatch(IndexerEvents::BEFORE_STORAGE_UPDATE_DOCUMENT, $event)->isPropagationStopped()) {
                    continue;
                }

                $storage->updateDocument($document);

                $event = new DocumentEvent($document);
                $this->dispatcher->dispatch(IndexerEvents::STORAGE_UPDATE_DOCUMENT, $event);
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

    /**
     * @param DocumentInterface $document
     *
     * @return $this
     */
    public function addDocument(DocumentInterface $document)
    {
        return $this->addCommand(new AddDocumentCommand($document));
    }

    /**
     * @param DocumentInterface $document
     *
     * @return $this
     */
    public function updateDocument(DocumentInterface $document)
    {
        return $this->addCommand(new UpdateDocumentCommand($document));
    }

    /**
     * @param DocumentInterface $document
     *
     * @return $this
     */
    public function deleteDocument(DocumentInterface $document)
    {
        return $this->addCommand(new DeleteDocumentCommand($document));
    }

    /**
     * @param string $identifier
     *
     * @return $this
     */
    public function delete($identifier)
    {
        return $this->addCommand(new DeleteCommand($identifier));
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function deleteType($type)
    {
        return $this->addCommand(new DeleteTypeCommand($type));
    }

    /**
     * @return $this
     */
    public function commit()
    {
        return $this->addCommand(new CommitCommand());
    }

    /**
     * @return $this
     */
    public function rollback()
    {
        return $this->addCommand(new RollbackCommand());
    }

    /**
     * @return $this
     */
    public function flush()
    {
        return $this->addCommand(new FlushCommand());
    }

    /**
     * @return $this
     */
    public function optimize()
    {
        return $this->addCommand(new OptimizeCommand());
    }

    /**
     * @param CommandInterface $command
     *
     * @return $this
     */
    private function addCommand(CommandInterface $command)
    {
        $this->commands[] = $command;

        return $this;
    }
}
