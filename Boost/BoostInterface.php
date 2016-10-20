<?php

/*
 * This file is part of the phlexible indexer package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\IndexerBundle\Boost;

/**
 * Boost interface.
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
interface BoostInterface
{
    /**
     * @param string $field
     *
     * @return float
     */
    public function getBoost($field);

    /**
     * @param string $field
     *
     * @return float
     */
    public function getPrecision($field);

    /**
     * @param string $field
     * @param float  $boost
     *
     * @return $this
     */
    public function addFieldBoost($field, $boost);

    /**
     * @param string $field
     * @param float  $precision
     *
     * @return $this
     */
    public function addFieldPrecision($field, $precision);
}
