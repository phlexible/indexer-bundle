<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Storage;

use Phlexible\Bundle\IndexerBundle\Event\DocumentEvent;
use Phlexible\Bundle\IndexerBundle\IndexerEvents;
use Phlexible\Bundle\IndexerBundle\Query\Query;
use Phlexible\Bundle\IndexerBundle\Result\Result;
use Phlexible\Bundle\IndexerBundle\Storage\SelectQuery\SelectQuery;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\AddCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\CommitCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\DeleteQueryCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\DeleteClassCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\FlushCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\OptimizeCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\UpdateCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\Command\DeleteCommand;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\UpdateQuery;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Storage
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class Storage implements StorageInterface
{
    /**
     * @var StorageAdapterInterface
     */
    private $adapter;

    /**
      * @var EventDispatcherInterface
      */
    private $dispatcher;

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param StorageAdapterInterface  $adapter
     */
    public function __construct(EventDispatcherInterface $dispatcher,
                                StorageAdapterInterface $adapter)
    {
        $this->dispatcher = $dispatcher;
        $this->adapter    = $adapter;
    }

    /**
     * @return StorageAdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @return integer
     */
    public function getPreference()
    {
        return $this->adapter->getPreference();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->adapter->getId();
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->adapter->getLabel();
    }

    /**
     * @return string
     */
    public function getResultClass()
    {
        return $this->adapter->getResultClass();
    }

    /**
     * @return SelectQuery
     */
    public function createSelect()
    {
        return new SelectQuery($this->dispatcher);
    }

    /**
     * @param SelectQuery $select
     *
     * @return Result
     */
    public function select(SelectQuery $select)
    {
        return $this->adapter->getByQuery($select);
    }

    /**
     * @return SuggestQuery
     */
    public function createSuggest()
    {
        return new SuggestQuery($this->dispatcher);
    }

    /**
     * @param SuggestQuery $query
     */
    public function suggest(SuggestQuery $query)
    {

    }

    /**
     * @return UpdateQuery
     */
    public function createUpdate()
    {
        return new UpdateQuery($this->dispatcher);
    }

    /**
     * @param UpdateQuery $update
     *
     * @return $this
     */
    public function update(UpdateQuery $update)
    {
        foreach ($update->getCommands() as $command) {
            if ($command instanceof AddCommand) {
                $document = $command->getDocument();
                $event = new DocumentEvent($document);
                if ($this->dispatcher->dispatch(IndexerEvents::BEFORE_STORAGE_ADD_DOCUMENT, $event)->isPropagationStopped()) {
                    continue;
                }

                $this->adapter->addDocument($command->getDocument());

                $event = new DocumentEvent($document);
                $this->dispatcher->dispatch(IndexerEvents::STORAGE_ADD_DOCUMENT, $event);
            } elseif ($command instanceof UpdateCommand) {
                $document = $command->getDocument();
                $event = new DocumentEvent($document);
                if ($this->dispatcher->dispatch(IndexerEvents::BEFORE_STORAGE_UPDATE_DOCUMENT, $event)->isPropagationStopped()) {
                    continue;
                }

                $this->adapter->updateDocument($document);

                $event = new DocumentEvent($document);
                $this->dispatcher->dispatch(IndexerEvents::STORAGE_UPDATE_DOCUMENT, $event);
            } elseif ($command instanceof DeleteCommand) {
                $this->adapter->removeByIdentifier($command->getDocument());
            } elseif ($command instanceof DeleteQueryCommand) {
                $this->adapter->removeByQuery($command->getQuery());
            } elseif ($command instanceof DeleteClassCommand) {
                $this->adapter->removeByClass($command->getClass());
            } elseif ($command instanceof FlushCommand) {
                $this->adapter->removeAll();
            } elseif ($command instanceof OptimizeCommand) {
                $this->adapter->optimize();
            } elseif ($command instanceof CommitCommand) {
                $this->adapter->commit();
            }
        }


        return $this;
    }

    /**
     * @return boolean
     */
    public function isOptimizable()
    {
        return $this->adapter instanceof Optimizable;
    }

    /**
     * @return $this
     */
    public function optimize()
    {
        if ($this->isOptimizable()) {
            $this->adapter->optimize();
        }

        return $this;
    }

    /**
     * @return boolean
     */
    public function isHealthy()
    {
        return $this->adapter->isHealthy();
    }
}
