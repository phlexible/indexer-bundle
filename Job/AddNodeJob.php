<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Job;

use JobException;
use Phlexible\Bundle\IndexerBundle\Document\DocumentInterface;
use Phlexible\Bundle\IndexerBundle\Indexer\IndexerInterface;
use Phlexible\Bundle\MediaCacheBundle\Storage\StorageInterface;
use Phlexible\Bundle\QueueBundle\Job\ContainerAwareJob;

/**
 * Add node job
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class AddNodeJob extends ContainerAwareJob
{
    /**
     * @var string
     */
    protected $_identifier = null;

    /**
     * @var string
     */
    protected $_indexerId = null;

    /**
     * @var array
     */
    protected $_storageIds = array();

    /**
     * @return IndexerInterface
     */
    public function getIndexerId()
    {
        return $this->_indexerId;
    }

    /**
     * Set indexer
     *
     * @param string $indexerId
     * @return $this
     */
    public function setIndexerId($indexerId)
    {
        $this->_indexerId = $indexerId;

        return $this;
    }

    /**
     * Return identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->_identifier;
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return $this
     */
    public function setIdentifier($identifier)
    {
        $this->_identifier = $identifier;

        return $this;
    }

    /**
     * Return storages
     *
     * @return array
     */
    public function getStorageIds()
    {
        return $this->_storageIds;
    }

    /**
     * Set storages IDs
     *
     * @param array $storageIds
     * @return $this
     */
    public function setStorageIds(array $storageIds)
    {
        $this->_storageIds = $storageIds;

        return $this;
    }

    /**
     * @see MWF_Core_Queue_Job_Abstract::work()
     *
     * @throws JobException
     * @throws Exception if on occurs in <storage>::addDocument
     */
    public function work()
    {
        if (!strlen($this->_identifier))
        {
            throw new JobException('Got empty Identifier!');
        }

        $container = $this->_container;

        $indexers = $container->get('indexer.indexers');
        $storages = $container->get('indexer.storages');

        $indexer = $indexers[$this->_indexerId];

        $document = $indexer->getDocumentByIdentifier($this->_identifier);

        if (null === $document)
        {
            \MWF_Log::debug(__METHOD__ . " Document for $this->_identifier is null");
            return;
        }

        if (!$document instanceof DocumentInterface)
        {
            throw new JobException(
                'Indexable has no MWF_Core_Indexer_Document_Interface found '
            );
        }

        $indexerTools = $container->get('indexer.tools');
        $storages     = $indexerTools->filterRepositoriesByAcceptedStorage(
            $document->getDocumentType(),
            $storages
        );

        \MWF_Log::debug(__METHOD__ . " Document ID: $this->_identifier");
        \MWF_Log::debug(__METHOD__ . " Document Class: " . get_class($document));
        \MWF_Log::debug(__METHOD__ . " Indexer ID: $this->_indexerId");
        \MWF_Log::debug(__METHOD__ . " Indexer Class: " . get_class($indexer));

        foreach ($storages as $storageId => $storage)
        {
            /* @var $storage StorageInterface */

            // skip storages which are not relevant for this job
            if (!in_array($storageId, $this->_storageIds))
            {
                continue;
            }

            // skip storing document on unhelathy storages
            if (!$storage->isHealthy())
            {
                $msg = "Indexer storage '$storageId' is not healthy. Skiping adding document.";
                MWF_Log::warn($msg);
                continue;
            }

            \MWF_Log::debug(__METHOD__ . " Storage ID: $storageId");
            \MWF_Log::debug(__METHOD__ . " Storage Class: " . get_class($storage));
            \MWF_Log::debug(__METHOD__ . " Adapter Class: " . get_class($storage->getAdapter()));

            try
            {
                // add document to storage
                $storage->addDocument($document);

                \MWF_Log::debug(__METHOD__ . " Success: true");
            }
            catch (\Exception $e)
            {
                // catch exception to store document in all storages
                // -> the last catched exception is thrown at the end of method work()
                \MWF_Log::debug(__METHOD__ . " Success: false");
            }
        }

        // throw previously catched exception if one occured
        if (isset($e))
        {
            throw $e;
        }
    }
}
