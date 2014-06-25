<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Boost;

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
    private $defaultBoost = 1.0;

    /**
     * @var float
     */
    private $defaultPrecision = 0.7;

    /**
     * @var array
     */
    private $fieldBoost = array('unittestvalue' => 2.0);

    /**
     * @var array
     */
    private $fieldPrecision = array('unittestvalue' => 2.0);

    /**
     * {@inheritdoc}
     */
    public function getBoost($field)
    {
        if (true === array_key_exists($field, $this->fieldBoost)) {
            return (float) $this->fieldBoost[$field];
        }

        return (float) $this->defaultBoost;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrecision($field)
    {
        if (true === array_key_exists($field, $this->fieldPrecision)) {
            return (float) $this->fieldPrecision[$field];
        }

        return (float) $this->defaultPrecision;
    }

    /**
     * {@inheritdoc}
     */
    public function addFieldBoost($field, $boost)
    {
        $this->fieldBoost[$field] = $boost;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addFieldPrecision($field, $precision)
    {
        $this->fieldPrecision[$field] = $precision;

        return $this;
    }
}