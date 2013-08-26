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
 * Indexer Search
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
class MWF_Core_Indexer_Search implements MWF_Core_Indexer_Search_Interface
{
    /**
     * @var MWF_Component_Manager
     */
    protected $_componentManager = null;

    /**
     * @var MWF_Core_Indexer_Tools
     */
    protected $_indexerTools = null;

    /**
     * @var MWF_Core_Indexer_Result_Interface
     */
    protected $_indexerResult = null;

    /**
     * @param MWF_Component_Manager             $componentManager
     * @param MWF_Core_Indexer_Tools            $indexerTools
     * @param MWF_Core_Indexer_Result_Interface $indexerResult
     */
    public function __construct(MWF_Component_Manager             $componentManager,
                                MWF_Core_Indexer_Tools            $indexerTools,
                                MWF_Core_Indexer_Result_Interface $indexerResult)
    {
        $this->_componentManager = $componentManager;
        $this->_indexerTools     = $indexerTools;
        $this->_indexerResult    = $indexerResult;
    }

    /**
     * @param MWF_Core_Indexer_Query_Interface $query
     *
     * @return MWF_Core_Indexer_Result_Interface $indexerResult
     */
    public function query(MWF_Core_Indexer_Query_Interface $query)
    {
        $this->_indexerResult->clear();

        $storages = $this->_getRepositoriesByQuery($query);

        foreach ($storages as $storage)
        {
            $storageResult = $storage->getByQuery($query);
            $this->_indexerResult->addResult($storageResult);
        }

        $this->_indexerResult->sort();

        return $this->_indexerResult;
    }

    /**
     * @param string $query
     *
     * @throws MWF_Core_Indexer_Exception
     */
    protected function _getRepositoriesByQuery($query)
    {
        // get repositories that understand query-class and have the matching nodes
        $storages = $this->_indexerTools->getRepositoriesForSearch($query);

        // ======
        // @TODO move to MWF_Core_Indexer_Tools::getRepositoriesForSearch() ?
        $healthyStorages = array();
        $downlist = array();

        foreach ($storages as $key => $storage)
        {
            if ($storage->isHealthy())
            {
                $healthyStorages[$key] = $storage;
            }
            else
            {
                $downlist[$key] = $storage->getLabel();
            }
        }

        if (!count($healthyStorages))
        {
            $msg = 'NodeSearch for query "' . get_class($query) . '" has no healthy storage.'
                 . ' (Down: ' . implode(', ', $downlist) . ')';

            throw new MWF_Core_Indexer_Exception($msg);
        }
        // ======

        return $healthyStorages;
    }

    /**
     * @param MWF_Core_Indexer_Result_Sorter_Interface $sorter
     */
    public function setSorter(MWF_Core_Indexer_Result_Sorter_Interface $sorter)
    {
        $this->_indexerResult->setSorter($sorter);
    }

}