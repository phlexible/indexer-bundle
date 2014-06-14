<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Search;

use Phlexible\IndexerComponent\IndexerTools;
use Phlexible\IndexerComponent\Query\QueryInterface;
use Phlexible\IndexerComponent\Result\ResultInterface;
use Phlexible\IndexerComponent\Result\Sorter\SorterInterface;

/**
 * Indexer search
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class Search implements SearchInterface
{
    /**
     * @param QueryInterface $query
     *
     * @return ResultInterface $indexerResult
     */
    public function query(QueryInterface $query)
    {
        $this->indexerResult->clear();

        $storages = $this->_getRepositoriesByQuery($query);

        foreach ($storages as $storage)
        {
            $storageResult = $storage->getByQuery($query);
            $this->indexerResult->addResult($storageResult);
        }

        $this->indexerResult->sort();

        return $this->indexerResult;
    }

    /**
     * @param string $query
     *
     * @throws \Exception
     */
    protected function _getRepositoriesByQuery($query)
    {
        // get repositories that understand query-class and have the matching nodes
        $storages = $this->indexerTools->getRepositoriesForSearch($query);

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

            throw new \Exception($msg);
        }
        // ======

        return $healthyStorages;
    }

    /**
     * @param SorterInterface $sorter
     */
    public function setSorter(SorterInterface $sorter)
    {
        $this->indexerResult->setSorter($sorter);
    }

}