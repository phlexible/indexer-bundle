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
 * Search Interface
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
interface MWF_Core_Indexer_Search_Interface
{
    const QUERY_FIRST = 'query_first';
    const QUERY_ALL   = 'query_all';
    const QUERY_BEST  = 'query_best';

    /**
     * @param MWF_Core_Indexer_Query_Interface $query
     *
     * @return MWF_Core_Indexer_Result_Interface $indexerResult
     */
    public function query(MWF_Core_Indexer_Query_Interface $query);

    /**
     * @param MWF_Core_Indexer_Result_Sorter_Interface $sorter
     */
    public function setSorter(MWF_Core_Indexer_Result_Sorter_Interface $sorter);

}