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
 * Abstract Boost
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
abstract class MWF_Core_Indexer_Boost_Abstract implements MWF_Core_Indexer_Boost_Interface
{
    /**
     * @var float
     */
    protected $_baseBoost = 1.0;

    /**
     * @var float
     */
    protected $_basePrecision = 0.7;

    /**
     * @var array
     */
    protected $_customBoosts = array('unittestvalue' => 2.0);

    /**
     * @var array
     */
    protected $_customPrecision = array('unittestvalue' => 2.0);

    /**
     * Return boost
     *
     * @return float
     */
    public function getBoost($field)
    {
        if (true === array_key_exists($field, $this->_customBoosts))
        {
            return (float) $this->_customBoosts[$field];
        }

        return (float)$this->_baseBoost;
    }

    /**
     * Return precision
     *
     * @return float
     */
    public function getPrecision($field)
    {
        if (true === array_key_exists($field, $this->_customPrecision))
        {
            return (float) $this->_customPrecision[$field];
        }

        return (float)$this->_basePrecision;
    }
}