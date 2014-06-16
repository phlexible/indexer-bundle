<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Document;

/**
 * Document interface
 *
 * @author Marco Fischer <mf@brainbits.net>
 * @author Phillip Look <pl@brainbits.net>
 */
interface DocumentInterface extends \ArrayAccess
{
    const CONFIG_NOTINDEXED = 'indexed';
    const CONFIG_MULTIVALUE = 'multivalue';
    const CONFIG_COPY       = 'copy';
    const CONFIG_READONLY   = 'readonly';
    const CONFIG_HIGHLIGHT  = 'highlight';

    /**
     * @param string $documentType
     */
    public function __construct($documentType);

    /**
     * Magic __get
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key);

    /**
     * Magic __set
     *
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key, $value);

    /**
     * Magic __isset
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key);

    /**
     * Magic __toString
     *
     * @return string
     */
    public function __toString();

    /**
     * Get values
     *
     * @return array
     */
    public function getValues();

    /**
     * Set values
     *
     * @param array   $values
     * @param boolean $implicitCreateField
     * @return $this
     */
    public function setValues($values, $implicitCreateField = false);

    /**
     * Is value set?
     *
     * @param string $key
     * @return boolean
     */
    public function hasValue($key);

    /**
     * Get value
     *
     * @param string $key
     * @return array
     */
    public function getValue($key);

    /**
     * Set value
     *
     * @param string  $key
     * @param string  $value
     * @param boolean $implicitCreateField
     * @return $this
     */
    public function setValue($key, $value, $implicitCreateField = false);

    /**
     * Is field available?
     *
     * @param string $key
     * @return boolean
     */
    public function hasField($key);

    /**
     * Set fields
     *
     * @param array $fields
     * @return $this
     */
    public function setFields(array $fields);

    /**
     * Get fields
     *
     * @return array
     */
    public function getFields();

    /**
     * Set field
     *
     * @param string $key
     * @param array  $config
     * @return $this
     */
    public function setField($key, array $config = array());

    /**
     * Get field
     *
     * @param string $key
     * @return array
     */
    public function getField($key);

    /**
     * Remove a field
     *
     * @param string $key
     * @return $this
     */
    public function removeField($key);

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Set identifier
     *
     * @param string $id
     * @return $this
     */
    public function setIdentifier($id);

    /**
     * Return document class
     *
     * @return string
     */
    public function getDocumentClass();

    /**
     * Return document type
     *
     * @return string
     */
    public function getDocumentType();

    /**
     * Set relevance
     *
     * @param mixed $relevance
     * @return $this
     */
    public function setRelevance($relevance);

    /**
     * Get relevance
     *
     * @return mixed
     */
    public function getRelevance();
}