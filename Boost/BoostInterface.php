<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Boost;

/**
 * Boost interface
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