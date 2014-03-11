<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Boost;

/**
 * Abstract boost
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
abstract class AbstractBoost implements BoostInterface
{
    /**
     * @var float
     */
    protected $baseBoost = 1.0;

    /**
     * @var float
     */
    protected $basePrecision = 0.7;

    /**
     * @var array
     */
    protected $customBoosts = array('unittestvalue' => 2.0);

    /**
     * @var array
     */
    protected $customPrecision = array('unittestvalue' => 2.0);

    /**
     * {@inheritdoc}
     */
    public function getBoost($field)
    {
        if (true === array_key_exists($field, $this->customBoosts))
        {
            return (float) $this->customBoosts[$field];
        }

        return (float)$this->baseBoost;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrecision($field)
    {
        if (true === array_key_exists($field, $this->customPrecision))
        {
            return (float) $this->customPrecision[$field];
        }

        return (float)$this->basePrecision;
    }
}