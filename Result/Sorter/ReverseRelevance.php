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
 * Reverse Relevance Sorter
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
class MWF_Core_Indexer_Result_Sorter_RelevanceReverse extends MWF_Core_Indexer_Result_Sorter_Relevance
{
    protected function _compare(MWF_Core_Indexer_Document_Interface $a,
                                MWF_Core_Indexer_Document_Interface $b)
    {
	    return -1 * parent::_compare($a, $b);
    }
}