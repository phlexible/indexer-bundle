<?php

/*
 * This file is part of the phlexible indexer package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\IndexerBundle\Document;

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
