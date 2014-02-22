<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Document;

use Phlexible\IndexerComponent\Exception\InvalidArgumentException;

/**
 * Abstract document
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
abstract class AbstractDocument implements DocumentInterface, Boostable
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
     * @var \Zend_Filter_Interface
     */
    protected $_fieldNameFilter;

    /**
     * @param string $documentType
     */
    public function __construct($documentType)
    {
        $this->_documentType = $documentType;
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
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
     * @inheritDoc
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
     * @inheritDoc
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
     * ArrayAccess exists
     *
     * @param mixed $index
     * @return boolean
     */
    public function offsetExists($index)
    {
        return $this->hasValue($index);
    }

    /**
     * ArrayAccess get
     *
     * @param mixed $index
     * @return mixed
     */
    public function offsetGet($index)
    {
        return $this->getValue($index);
    }

    /**
     * ArrayAccess set
     *
     * @param mixed $index
     * @param mixed $value
     */
    public function offsetSet($index, $value)
    {
        $this->setValue($index, $value);
    }

    /**
     * ArrayAccess unset
     */
    public function offsetUnset($index)
    {
        unset($this->_values[$index]);
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
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
     * @inheritDoc
     */
    public function hasValue($key)
    {
        return isset($this->_values[$key]);
    }

    /**
     * @inheritDoc
     */
    public function getValue($key)
    {
        if (!$this->hasField($key))
        {
            throw new InvalidArgumentException('Unknown field "' . $key . '"');
        }

        if (!$this->hasValue($key))
        {
            return null;
        }

        return $this->_values[$key];
    }

    /**
     * @inheritDoc
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
                    $config[] = self::CONFIG_MULTIVALUE;
                }

                $this->setField($key, $config);
            }
            else
            {
                throw new InvalidArgumentException('Unknown field "' . $key . '"');
            }
        }
        
        if (isset($this->_fields[$key][self::CONFIG_MULTIVALUE]))
        {
            $value = (array) $value;
        }

        $this->_values[$key] = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function hasField($key)
    {
        return isset($this->_fields[$key]);
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function getField($key)
    {
        return $this->_fields[$key];
    }
    
    /**
     * @inheritDoc
     */
    public function removeField($key)
    {
        unset($this->_fields[$key], $this->_values[$key]);
        
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        return $this->_id;
    }

    /**
     * @inheritDoc
     */
    public function setIdentifier($id)
    {
        $this->_id = $id;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setBoost($boost)
    {
        $this->_boost = $boost;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getBoost()
    {
        return $this->_boost;
    }

    /**
     * @inheritDoc
     */
    public function getDocumentClass()
    {
        return get_class($this);
    }

    /**
     * @inheritDoc
     */
    public function getDocumentType()
    {
        return $this->_documentType;
    }

    /**
     * @inheritDoc
     */
    public function setRelevance($relevance)
    {
        $this->_relevance = $relevance;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRelevance()
    {
        return $this->_relevance;
    }

    /**
     * Set field name filter.
     *
     * @param \Zend_Filter_Interface $fieldNameFilter
     * @return $this
     */
    public function setFieldNameFilter(\Zend_Filter_Interface $fieldNameFilter)
    {
        $this->_fieldNameFilter = $fieldNameFilter;

        return $this;
    }
}
