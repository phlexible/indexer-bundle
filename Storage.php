<?php
/**
 * Phlexible
 *
 * PHP Version 5
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */

/**
 * Storage
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
class MWF_Core_Indexer_Storage
{
    /**
     * @var MWF_Core_Indexer_Storage_Adapter_Interface
     */
    protected $_adapter = null;

    /**
      * @var Brainbits_Event_Dispatcher
      */
    protected $_dispatcher;

    /**
     * @param Brainbits_Event_Dispatcher                 $dispatcher
     * @param MWF_Core_Indexer_Storage_Adapter_Interface $adapter
     */
    public function __construct(Brainbits_Event_Dispatcher $dispatcher,
                                MWF_Core_Indexer_Storage_Adapter_Interface $adapter)
    {
        $this->_dispatcher = $dispatcher;
        $this->_adapter    = $adapter;
    }

    public function getAdapter()
    {
        return $this->_adapter;
    }

    public function getPreference()
    {
        return $this->_adapter->getPreference();
    }

    public function getId()
    {
        return $this->_adapter->getId();
    }

    public function getLabel()
    {
        return $this->_adapter->getLabel();
    }

    public function getResultClass()
    {
        return $this->_adapter->getResultClass();
    }

    public function getAcceptQuery()
    {
        return $this->_adapter->getAcceptQuery();
    }

    public function getAcceptStorage()
    {
        return $this->_adapter->getAcceptStorage();
    }

    public function getByIdentifier($identifier = null)
    {
        return $this->_adapter->getByIdentifier($identifier);
    }

    /**
     * Return by query
     *
     * @param MWF_Core_Indexer_Query_Interface $query
     *
     * @return array
     */
    public function getByQuery(MWF_Core_Indexer_Query_Interface $query)
    {
        return $this->_adapter->getByQuery($query);
    }

    public function getAll()
    {
        return $this->_adapter->getAll();
    }

    public function addDocument(MWF_Core_Indexer_Document_Interface $document)
    {
        $beforeEvent = new MWF_Core_Indexer_Event_BeforeStorageAddDocumentEvent($document);
        if (!$this->_dispatcher->postNotification($beforeEvent))
        {
            return;
        }

        $this->_adapter->addDocument($document);

        $event = new MWF_Core_Indexer_Event_StorageAddDocumentEvent($document);
        $event->setBeforeNotification($beforeEvent);
        $this->_dispatcher->postNotification($event);
    }

    public function addDocuments(array $documents = array())
    {
        foreach ($documents as $document)
        {
            $this->addDocument($document);
        }
    }

    public function updateDocument(MWF_Core_Indexer_Document_Interface $document)
    {
        $beforeEvent = new MWF_Core_Indexer_Event_BeforeStorageUpdateDocumentEvent($document);
        if (!$this->_dispatcher->postNotification($beforeEvent))
        {
            return;
        }

        $this->_adapter->updateDocument($document);

        $event = new MWF_Core_Indexer_Event_StorageUpdateDocumentEvent($document);
        $event->setBeforeNotification($beforeEvent);
        $this->_dispatcher->postNotification($event);
    }

    public function removeByIdentifier($identifier = null)
    {
        $beforeEvent = new MWF_Core_Indexer_Event_BeforeStorageRemoveByIdentifierEvent($identifier);
        if (!$this->_dispatcher->postNotification($beforeEvent))
        {
            return;
        }

        $this->_adapter->removeByIdentifier($identifier);

        $event = new MWF_Core_Indexer_Event_StorageRemoveByIdentifierEvent($identifier);
        $event->setBeforeNotification($beforeEvent);
        $this->_dispatcher->postNotification($event);
    }

    /**
     * Remove by query
     *
     * @param MWF_Core_Indexer_Query_Interface $query
     */
    public function removeByQuery(MWF_Core_Indexer_Query_Interface $query)
    {
        $beforeEvent = new MWF_Core_Indexer_Event_BeforeStorageRemoveByQueryEvent($query);
        if (!$this->_dispatcher->postNotification($beforeEvent))
        {
            return;
        }

        $this->_adapter->removeByQuery($query);

        $event = new MWF_Core_Indexer_Event_StorageRemoveByQueryEvent($query);
        $event->setBeforeNotification($beforeEvent);
        $this->_dispatcher->postNotification($event);
    }

    public function removeAll()
    {
        $beforeEvent = new MWF_Core_Indexer_Event_BeforeStorageRemoveAllEvent();
        if (!$this->_dispatcher->postNotification($beforeEvent))
        {
            return;
        }

        $this->_adapter->removeAll();

        $event = new MWF_Core_Indexer_Event_StorageRemoveAllEvent();
        $event->setBeforeNotification($beforeEvent);
        $this->_dispatcher->postNotification($event);
    }

    public function isHealthy()
    {
        return $this->_adapter->isHealthy();
    }

    public function optimize()
    {
        if (!$this->_adapter instanceof MWF_Core_Indexer_Storage_Adapter_Optimizable)
        {
            return false;
        }

        $this->_adapter->optimize();

        return true;
    }
}
