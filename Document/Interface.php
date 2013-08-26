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
 * Document Interface
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @author      Phillip Look <pl@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
interface MWF_Core_Indexer_Document_Interface extends ArrayAccess
{
    const CONFIG_NOTINDEXED = 'indexed';
    const CONFIG_MULTIVALUE = 'multivalue';
    const CONFIG_COPY       = 'copy';
    const CONFIG_READONLY   = 'readonly';
    const CONFIG_HIGHLIGHT  = 'highlight';

    /**
     * Constructor
     *
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
     * @throws RuntimeException
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
     * @return MWF_Core_Indexer_Document_Abstract
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
     * @return MWF_Core_Indexer_Document_Abstract
     */
    public function setValue($key, $value, $implicitCreateField = false);

    /**
     * Is field available?
     *
     * @param string $field
     * @return boolean
     */
    public function hasField($key);

    /**
     * Set fields
     *
     * @param array $fields
     * @return MWF_Core_Indexer_Document_Abstract
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
     * @return MWF_Core_Indexer_Document_Abstract
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
     * @return MWF_Core_Indexer_Document_Abstract
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
     * return MWF_Core_Indexer_Document_Abstract
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
     * @return MWF_Core_Indexer_Document_Abstract
     */
    public function setRelevance($relevance);

    /**
     * Get relevance
     *
     * @return mixed
     */
    public function getRelevance();
}