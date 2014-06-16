<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Query;
use Phlexible\IndexerBundle\Boost\BoostInterface;

/**
 * Query interface
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
interface QueryInterface
{
    public function addField($field);

    public function setFields(array $fields);

    public function getFields();

    /**
     * Add a document type to find.
     *
     * @param string $documentType
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
     * @param QueryInterface $input
     */
    public function parseInput($input);

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @return QueryParserInterface|null
     */
    public function getParser();

    /**
     * @return BoostInterface|null
     */
    public function getBoost();

    /**
     * Add a filter
     *
     * @param string $key
     * @param string $value
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
     * @param string $key
     * @return string
     */
    public function getFilter($key);

    /**
     * Check if filter exists
     *
     * @param string $key
     * @return boolean
     */
    public function hasFilter($key);

    public function toString();

    public function __toString();
}