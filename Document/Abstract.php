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
 * Abstract Document
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
abstract class MWF_Core_Indexer_Document_Abstract
    implements MWF_Core_Indexer_Document_Interface, MWF_Core_Indexer_Document_Boostable
{
    /**
     * @var string
     */
    protected $_id;

    /**
     * @var integer
     */
    protected $_relevance = 0;

    /**
     * @var float
     */
    protected $_boost = 1.0;

    /**
     * @var array
     */
    protected $_fields = array();

    /**
     * @var array
     */
    protected $_values = array();

    /**
     * @var string
     */
    protected $_documentType;

    /**
     * @var Zend_Filter_Interface
     */
    protected $_fieldNameFilter;

    /**
     * Constructor
     *
     * @param string $documentType
     */
    public function __construct($documentType)
    {
        $this->_documentType = $documentType;
    }

    /**
     * Magic __get
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        if ($this->_fieldNameFilter)
        {
            $filteredKey = $this->_fieldNameFilter->filter($key);
            if ($this->hasField($filteredKey))
            {
                $key = $filteredKey;
            }
        }

        return $this->getValue($key);
    }

    /**
     * Magic __set
     *
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key, $value)
    {
        if ($this->_fieldNameFilter)
        {
            $filteredKey = $this->_fieldNameFilter->filter($key);
            if ($this->hasField($filteredKey))
            {
                $key = $filteredKey;
            }
        }

        $this->setValue($key, $value);
    }

    /**
     * Magic __isset
     *
     * @param string $key
     * @throws RuntimeException
     * @return boolean
     */
    public function __isset($key)
    {
        if ($this->_fieldNameFilter)
        {
            $filteredKey = $this->_fieldNameFilter->filter($key);
            if ($this->hasField($filteredKey))
            {
                $key = $filteredKey;
            }
        }

        return $this->hasValue($key);
    }

    /**
     * Magic __toString
     *
     * @return string
     */
    public function __toString()
    {
        $output = 'Identifier: ' . $this->getIdentifier()
                . ', DocumentClass: ' . $this->getDocumentClass()
                . ', Relevance: ' . $this->getRelevance() . PHP_EOL;

        foreach ($this->_fields as $key => $config)
        {
            $output .= $key . ': ' . $this->getValue($key) . ' (';

            $dummy = array();
            foreach ($config as $configKey => $configValue)
            {
                $dummy[] = $configKey . ':' . $configValue;
            }

            $output .= implode(',', $dummy) . ')';
        }

        return $output;
    }

    /**
     * Exists ArrayAccess
     *
     * @return boolean
     */
    public function offsetExists($index)
    {
        return $this->hasValue($index);
    }

    /**
     * Get ArrayAccess
     *
     * @param string $index
     * @return mixed
     */
    public function offsetGet($index)
    {
        return $this->getValue($index);
    }

    /**
     * Set ArrayAccess
     *
     * @param string $index
     * @param mixed $newval
     */
    public function offsetSet($index, $newval)
    {
        $this->setValue($index, $newval);
    }

    /**
     * Unset ArrayAccess
     */
    public function offsetUnset($index)
    {
        unset($this->_values[$index]);
    }

    /**
     * Get values
     *
     * @return array
     */
    public function getValues()
    {
        $values = array(
            '_identifier_'    => $this->getIdentifier(),
            '_documentclass_' => $this->getDocumentClass(),
            '_documenttype_'  => $this->getDocumentType(),
        );

        return $values + $this->_values;
    }

    /**
     * Set values
     *
     * @param array   $values
     * @param boolean $implicitCreateField
     * @return MWF_Core_Indexer_Document_Abstract
     */
    public function setValues($values, $implicitCreateField = false)
    {
        foreach ($values as $key => $value)
        {
            if ($key[0] == '_')
            {
                continue;
            }

            $this->setValue($key, $value, $implicitCreateField);
        }

        return $this;
    }

    /**
     * Is value set?
     *
     * @param string $key
     * @return boolean
     */
    public function hasValue($key)
    {
        return isset($this->_values[$key]);
    }

    /**
     * Get value
     *
     * @param string $key
     * @return mixed
     */
    public function getValue($key)
    {
        if (!$this->hasField($key))
        {
            throw new MWF_Core_Indexer_Exception('Unknown field "' . $key . '"');
        }

        if (!$this->hasValue($key))
        {
            return null;
        }

        return $this->_values[$key];
    }

    /**
     * Set value
     *
     * @param string  $key
     * @param string  $value
     * @param boolean $implicitCreateField
     * @return MWF_Core_Indexer_Document_Abstract
     */
    public function setValue($key, $value, $implicitCreateField = false)
    {
        if (!$this->hasField($key))
        {
            if ($implicitCreateField)
            {
                $config = array();

                if (is_array($value))
                {
                    $config[] = MWF_Core_Indexer_Document_Interface::CONFIG_MULTIVALUE;
                }

                $this->setField($key, $config);
            }
            else
            {
                throw new MWF_Core_Indexer_Exception('Unknown field "' . $key . '"');
            }
        }
        
        if (isset($this->_fields[$key][MWF_Core_Indexer_Document_Interface::CONFIG_MULTIVALUE]))
        {
            $value = (array) $value;
        }

        $this->_values[$key] = $value;

        return $this;
    }

    /**
     * Is field available?
     *
     * @param string $field
     * @return boolean
     */
    public function hasField($key)
    {
        return isset($this->_fields[$key]);
    }

    /**
     * Set fields
     *
     * @param array $fields
     * @return MWF_Core_Indexer_Document_Abstract
     */
    public function setFields(array $fields)
    {
        foreach ($fields as $key => $config)
        {
            $this->setField($key, $config);
        }

        return $this;
    }

    /**
     * Get fields
     *
     * @return array
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * Set field
     *
     * @param string $key
     * @param array  $config
     * @return MWF_Core_Indexer_Document_Abstract
     */
    public function setField($key, array $config = array())
    {
        $this->_fields[$key] = array();

        foreach ($config as $configKey)
        {
            $this->_fields[$key][$configKey] = true;
        }

        return $this;
    }

    /**
     * Get field
     *
     * @param string $key
     * @return array
     */
    public function getField($key)
    {
        return $this->_fields[$key];
    }
    
    /**
     * Remove a field
     *
     * @param string $key
     * @return MWF_Core_Indexer_Document_Abstract
     */
    public function removeField($key)
    {
        unset($this->_fields[$key], $this->_values[$key]);
        
        return $this;
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->_id;
    }

    /**
     * Set identifier
     *
     * @param string $id
     * return MWF_Core_Indexer_Document_Abstract
     */
    public function setIdentifier($id)
    {
        $this->_id = $id;

        return $this;
    }

    /**
     * Sets boost-factor of document.
     * default is 1.0
     *
     * @param float $boost
     * @return MWF_Core_Indexer_Document_Abstract
     */
    public function setBoost($boost)
    {
        $this->_boost = $boost;

        return $this;
    }

    /**
     * Returns boost-factor of document
     *
     * @return float
     */
    public function getBoost()
    {
        return $this->_boost;
    }

    /**
     * Return document class
     *
     * @return string
     */
    public function getDocumentClass()
    {
        return get_class($this);
    }

    /**
     * Return document type
     *
     * @return string
     */
    public function getDocumentType()
    {
        return $this->_documentType;
    }

    /**
     * Set relevance
     *
     * @param mixed $relevance
     * @return MWF_Core_Indexer_Document_Abstract
     */
    public function setRelevance($relevance)
    {
        $this->_relevance = $relevance;

        return $this;
    }

    /**
     * Get relevance
     *
     * @return mixed
     */
    public function getRelevance()
    {
        return $this->_relevance;
    }

    /**
     * Set field name filter.
     *
     * @param Zend_Filter_Interface $fieldNameFilter
     *
     * @return MWF_Core_Indexer_Document_Abstract
     */
    public function setFieldNameFilter(Zend_Filter_Interface $fieldNameFilter)
    {
        $this->_fieldNameFilter = $fieldNameFilter;

        return $this;
    }
}
