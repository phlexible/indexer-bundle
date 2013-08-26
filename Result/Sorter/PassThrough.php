<?php
/**
 * Phlexible
 *
 * PHP Version 5
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @copyright   2012 brainbits GmbH (http://www.brainbits.net)
 */

/**
 * Field Sorter
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Peter Fahsel <pfahsel@brainbits.net>
 * @copyright   2012 brainbits GmbH (http://www.brainbits.net)
 */
class MWF_Core_Indexer_Result_Sorter_PassThrough implements MWF_Core_Indexer_Result_Sorter_Interface
{
    public function sort(array $documents)
    {
        return $documents;
    }
}