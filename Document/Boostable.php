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
 * Boostable Interface
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
interface MWF_Core_Indexer_Document_Boostable
{
    /**
     * Sets boost-factor of document.
     * default is 1.0
     *
     * @param float $boost
     */
    public function setBoost($boost);

    /**
     * Returns boost-factor of document
     *
     * @return float
     */
    public function getBoost();
}