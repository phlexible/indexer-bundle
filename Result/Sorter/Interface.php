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
 * Result Sorter Interface
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @author      Phillip Look <pl@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
interface MWF_Core_Indexer_Result_Sorter_Interface
{
    const LIST_A_FIRST = -1;
    const LIST_B_FIRST = 1;
    const EQUALS = 0;

    /**
     * Sort documents
     *
     * @param array $documents
     * @return array
     */
    public function sort(array $documents);
}