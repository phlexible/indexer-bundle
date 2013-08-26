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
 * Result Identifier
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
class MWF_Core_Indexer_Result_Identifier extends Brainbits_Identifier
{
    /**
     * Create a new Indexer Result Identifier
     *
     * @param string $query
     * @param integer $mode
     */
    public function __construct($query)
    {
        parent::__construct('indexer_result', md5($query));
    }
}
