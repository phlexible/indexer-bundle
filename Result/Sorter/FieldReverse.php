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
 * Reverse Field Sorter
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Phillip Look <pl@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
class MWF_Core_Indexer_Result_Sorter_FieldReverse extends MWF_Core_Indexer_Result_Sorter_Field
{
    protected function _compare(MWF_Core_Indexer_Document_Interface $a,
                                MWF_Core_Indexer_Document_Interface $b)
    {
	    return -1 * parent::_compare($a, $b);
    }
}