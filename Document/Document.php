<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Document;

use Phlexible\Bundle\IndexerBundle\Exception\InvalidArgumentException;

/**
 * Document
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
abstract class Document implements DocumentInterface, Boostable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var integer
     */
    private $relevance = 0;

    /**
     * @var float
     */
    private $boost = 1.0;

    /**
     * @var array
     */
    private $fields = array();

    /**
     * @var array
     */
    private $values = array();

    /**
     * @var \Zend_Filter_Interface
     */
    private $fieldNameFilter;

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        $output = 'Identifier: ' . $this->getIdentifier() . PHP_EOL
            . 'DocumentClass: ' . $this->getDocumentClass() . PHP_EOL
            . 'Relevance: ' . $this->getRelevance() . PHP_EOL;

        foreach ($this->fields as $key => $config) {
            $output .= $key . ': ' . var_export($this->getValue($key), true) . ' (';

            $dummy = array();
            foreach ($config as $configKey => $configValue) {
                $dummy[] = $configKey . ':' . $configValue;
            }

            $output .= implode(',', $dummy) . ')' . PHP_EOL;
        }

        return $output;
    }

    /**
     * ArrayAccess exists
     *
     * @param mixed $index
     *
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
     *
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
     *
     * @param mixed $index
     */
    public function offsetUnset($index)
    {
        unset($this->values[$index]);
    }

    /**
     * {@inheritdoc}
     */
    public function getValues()
    {
        $values = array(
            '_identifier_'    => $this->getIdentifier(),
            '_documentclass_' => $this->getDocumentClass(),
        );

        return $values + $this->values;
    }

    /**
     * {@inheritdoc}
     */
    public function setValues($values, $implicitCreateField = false)
    {
        foreach ($values as $key => $value) {
            if ($key[0] == '_') {
                continue;
            }

            $this->setValue($key, $value, $implicitCreateField);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasValue($key)
    {
        return isset($this->values[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function getValue($key)
    {
        if (!$this->hasField($key)) {
            throw new InvalidArgumentException("Unknown field $key");
        }

        if (!$this->hasValue($key)) {
            return null;
        }

        return $this->values[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($key, $value, $implicitCreateField = false)
    {
        if (!$this->hasField($key)) {
            if ($implicitCreateField) {
                $config = array();

                if (is_array($value)) {
                    $config[] = self::CONFIG_MULTIVALUE;
                }

                $this->setField($key, $config);
            } else {
                throw new InvalidArgumentException("Unknown field $key");
            }
        }

        if (isset($this->fields[$key][self::CONFIG_MULTIVALUE])) {
            $value = (array) $value;
        }

        $this->values[$key] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasField($key)
    {
        return isset($this->fields[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function setFields(array $fields)
    {
        foreach ($fields as $key => $config) {
            $this->setField($key, $config);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * {@inheritdoc}
     */
    public function setField($key, array $config = array())
    {
        $this->fields[$key] = $config;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getField($key)
    {
        return $this->fields[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function removeField($key)
    {
        unset($this->fields[$key], $this->values[$key]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setIdentifier($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setBoost($boost)
    {
        $this->boost = $boost;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBoost()
    {
        return $this->boost;
    }

    /**
     * {@inheritdoc}
     */
    public function getDocumentClass()
    {
        return get_class($this);
    }

    /**
     * {@inheritdoc}
     */
    public function setRelevance($relevance)
    {
        $this->relevance = $relevance;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRelevance()
    {
        return $this->relevance;
    }

    /**
     * Set field name filter.
     *
     * @param \Zend_Filter_Interface $fieldNameFilter
     *
     * @return $this
     */
    public function setFieldNameFilter(\Zend_Filter_Interface $fieldNameFilter)
    {
        $this->fieldNameFilter = $fieldNameFilter;

        return $this;
    }

    /**
     * return field name filter.
     *
     * @return \Zend_Filter_Interface $fieldNameFilter
     */
    public function getFieldNameFilter()
    {
        return $this->fieldNameFilter;
    }
}