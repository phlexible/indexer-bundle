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
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->adapter->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return $this->adapter->getLabel();
    }

    /**
     * {@inheritdoc}
     */
    public function createQuery()
    {
        return new Query();
    }

    /**
     * {@inheritdoc}
     */
    public function query(Query $select)
    {
        return $this->adapter->getByQuery($select);
    }

    /**
     * {@inheritdoc}
     */
    public function createUpdate()
    {
        return new UpdateQuery($this->dispatcher);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function isOptimizable()
    {
        return $this->adapter instanceof Optimizable;
    }

    /**
     * {@inheritdoc}
     */
    public function optimize()
    {
        if ($this->isOptimizable()) {
            $this->adapter->optimize();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isHealthy()
    {
        return $this->adapter->isHealthy();
    }
}
