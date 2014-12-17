<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Document;

/**
 * Document interface
 *
 * @author Marco Fischer <mf@brainbits.net>
 * @author Phillip Look <pl@brainbits.net>
 */
interface DocumentInterface extends \ArrayAccess
{
    const CONFIG_NOTINDEXED = 'notindexed';
    const CONFIG_MULTIVALUE = 'multivalue';
    const CONFIG_READONLY   = 'readonly';
    const CONFIG_HIGHLIGHT  = 'highlight';
    const CONFIG_TYPE       = 'type';

    const TYPE_TEXT       = 'text';
    const TYPE_STRING     = 'string';
    const TYPE_INTEGER    = 'integer';
    const TYPE_FLOAT      = 'float';
    const TYPE_BOOLEAN    = 'boolean';
    const TYPE_DOUBLE     = 'double';
    const TYPE_LONG       = 'long';
    const TYPE_DATETIME   = 'datetime';
    const TYPE_DATE       = 'date';
    const TYPE_CURRENCY   = 'currency';

    /**
     * Return document name
     *
     * @return string
     */
    public function getName();

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
     *
     * @return $this
     */
    public function setValues($values, $implicitCreateField = false);

    /**
     * Is value set?
     *
     * @param string $key
     *
     * @return boolean
     */
    public function hasValue($key);

    /**
     * Return value
     *
     * @param string $key
     *
     * @return array
     */
    public function getValue($key);

    /**
     * Set value
     *
     * @param string  $key
     * @param string  $value
     * @param boolean $implicitCreateField
     *
     * @return $this
     */
    public function setValue($key, $value, $implicitCreateField = false);

    /**
     * Is field available?
     *
     * @param string $key
     *
     * @return boolean
     */
    public function hasField($key);

    /**
     * Set fields
     *
     * @param array $fields
     *
     * @return $this
     */
    public function setFields(array $fields);

    /**
     * Return fields
     *
     * @return array
     */
    public function getFields();

    /**
     * Set field
     *
     * @param string $key
     * @param array  $config
     *
     * @return $this
     */
    public function setField($key, array $config = array());

    /**
     * Return field
     *
     * @param string $key
     *
     * @return array
     */
    public function getField($key);

    /**
     * Remove a field
     *
     * @param string $key
     *
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
     *
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
     * Set relevance
     *
     * @param mixed $relevance
     *
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