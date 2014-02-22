<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Storage;

use Phlexible\Event\EventDispatcher;
use Phlexible\IndexerComponent\Document\DocumentInterface;
use Phlexible\IndexerComponent\Query\QueryInterface;
use Phlexible\IndexerComponent\Storage\UpdateQuery\Command\AddCommand;
use Phlexible\IndexerComponent\Storage\UpdateQuery\Command\FlushCommand;
use Phlexible\IndexerComponent\Storage\UpdateQuery\Command\UpdateCommand;
use Phlexible\IndexerComponent\Storage\UpdateQuery\UpdateQuery;

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
    protected $adapter = null;

    /**
      * @var EventDispatcher
      */
    protected $dispatcher;

    /**
     * @param EventDispatcher         $dispatcher
     * @param StorageAdapterInterface $adapter
     */
    public function __construct(EventDispatcher $dispatcher,
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
     * @return array
     */
    public function getAcceptQuery()
    {
        return $this->adapter->getAcceptQuery();
    }

    /**
     * @return array
     */
    public function getAcceptStorage()
    {
        return $this->adapter->getAcceptStorage();
    }

    /**
     * @param null $identifier
     * @return DocumentInterface
     */
    public function getByIdentifier($identifier = null)
    {
        return $this->adapter->getByIdentifier($identifier);
    }

    /**
     * @param QueryInterface $query
     * @return array
     */
    public function getByQuery(QueryInterface $query)
    {
        return $this->adapter->getByQuery($query);
    }

    public function getAll()
    {
        return $this->adapter->getAll();
    }

    /**
     * @return SelectQuery
     */
    public function createSelect()
    {
        return new SelectQuery();
    }

    /**
     * @param SelectQuery $select
     */
    public function select(SelectQuery $select)
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
     * @return $this
     */
    public function update(UpdateQuery $update)
    {
        #$beforeEvent = new BeforeStorageUpdateDocumentEvent($document);
        #if (!$this->dispatcher->dispatch($beforeEvent)) {
        #    return $this;
        #}

        foreach ($update->getCommands() as $command) {

            if ($command instanceof AddCommand) {
                $this->adapter->addDocument($command->getDocument());
            }
            elseif ($command instanceof UpdateCommand) {
                $this->adapter->updateDocument($command->getDocument());
            }
            elseif ($command instanceof FlushCommand) {
                $this->adapter->removeAll();
            }
        }

        #$event = new StorageUpdateDocumentEvent($document);
        #$event->setBeforeEvent($beforeEvent);
        #$this->dispatcher->dispatch($event);

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
