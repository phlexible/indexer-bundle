<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Document;

/**
 * Boostable interface
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
interface Boostable
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