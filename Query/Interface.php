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
 * Query Interface
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
interface MWF_Core_Indexer_Query_Interface
{
    public function addField($field);

    public function setFields(array $fields);

    public function getFields();

    /**
     * Add a document type to find.
     *
     * @param sting $interface
     */
    public function addDocumentType($documentType);

    /**
     * Set document types to find.
     *
     * @param array $documentTypes
     */
    public function setDocumentTypes(array $documentTypes);

    /**
     * Get document types to find.
     *
     * @return array
     */
    public function getDocumentTypes();

    /**
     * Parse the query string
     * @param MWF_Core_Indexer_Query_Interface $input
     */
    public function parseInput($input);

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @return MWF_Core_Indexer_Query_Parser_Interface|null
     */
    public function getParser();

    /**
     * @return MWF_Core_Indexer_Boost_Interface|null
     */
    public function getBoost();

    /**
     * Add a filter
     *
     * @param array $filters
     */
    public function addFilter($key, $value);

    /**
     * Set filters
     *
     * @param array $filters
     */
    public function setFilters(array $filters);

    /**
     * Return filters
     *
     * @return array
     */
    public function getFilters();

    /**
     * Get single filter value
     *
     * @param string $filters
     *
     * @return string
     */
    public function getFilter($key);

    /**
     * Check if filter exists
     *
     * @return boolean
     */
    public function hasFilter($key);

    public function toString();

    public function __toString();
}