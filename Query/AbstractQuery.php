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
 * Abstract query
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
abstract class AbstractQuery implements QueryInterface
{
    protected $_positive   = array();
    protected $_negative   = array();
    protected $_fields     = array();
    protected $_filters    = array();

    /**
     * Document types to find.
     *
     * @var array
     */
    protected $_documentTypes = array();

    /**
     * Define label in concrete class.
     *
     * @string
     */
    protected $_label;

    /**
     * @var BoostInterface
     */
    protected $_boost;

    /**
     * @var QueryParserInterface
     */
    protected $_parser;

    /**
     * @param QueryParserInterface $parser
     * @param BoostInterface       $boost
     */
    public function __construct(QueryParserInterface $parser,
                                BoostInterface $boost = null)
    {
        $this->_parser = $parser;
        $this->_boost  = $boost;
    }

    public function addField($field)
    {
        $this->_fields[] = $field;
    }

    public function setFields(array $fields)
    {
        $this->_fields = $fields;
    }

    public function getFields()
    {
        return $this->_fields;
    }

    public function setBoost(BoostInterface $boost)
    {
        $this->_boost = $boost;
    }

    /**
     * @return BoostInterface
     */
    public function getBoost()
    {
        return $this->_boost;
    }

    /**
     * Set parser
     *
     * @param QueryParserInterface $parser
     */
    public function setParser(QueryParserInterface $parser)
    {
        $this->_parser = $parser;
    }

    /**
     * Get parser
     *
     * @return QueryParserInterface
     */
    public function getParser()
    {
        return $this->_parser;
    }

    /**
     * Add a filter
     *
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function addFilter($key, $value)
    {
        if (null === $this->_filters)
        {
            $this->_filters = array();
        }

        $this->_filters[$key] = $value;
        return $this;
    }

    /**
     * Set filters
     *
     * @param array $filters
     * @return $this
     */
    public function setFilters(array $filters)
    {
        $this->_filters = $filters;
        return $this;
    }

    /**
     * Return filters
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->_filters;
    }

    /**
     * Get single filter value
     *
     * @param string $key
     * @return string|null
     */
    public function getFilter($key)
    {
        return $this->hasFilter($key)
            ? $this->_filters[$key]
            : null;
    }

    /**
     * Check if filter exists
     *
     * @param string $key
     * @return boolean
     */
    public function hasFilter($key)
    {
        return isset($this->_filters[$key]);
    }

    public function parseInput($input)
    {
        $this->_parser->parse($input);
        return $this;
    }

    public function getLabel()
    {
        if (null === $this->_label)
        {
            throw new QueryException('Define label in concrete class.');
        }

        return $this->_label;
    }

    public function toString()
    {
        $query = 'Terms: ';

        if (count($this->getParser()->getPositiveTerms()))
        {
            $query .= '+';
        }
        $query .= implode(',+', $this->getParser()->getPositiveTerms());

        if (count($this->getParser()->getNegativeTerms()))
        {
            $query .= '-';
        }
        $query .= implode(',-', $this->getParser()->getNegativeTerms());

        $query .= PHP_EOL;

        if (count($this->getFilters()))
        {
            $query .= 'Filters: ' . PHP_EOL . print_r($this->getFilters(), 1);

//            foreach ($this->getFilters() as $filterkey => $filterValue)
//            {
//                $query .= "$filterkey: ";
//                $query .= implode('|', (array) $filterValue);
//                $query .= ', ';
//            }
//            $query = substr($query, 0, -2);
        }
        $query .= implode(',-', $this->getParser()->getNegativeTerms());

        $query .= PHP_EOL;

        $query .= implode(',-', $this->getParser()->getNegativeTerms());
        $query .= 'Document types: ' . implode(', ', $this->_documentTypes) . PHP_EOL;
        $query .= 'Fields: ' . implode(', ', $this->_fields) . PHP_EOL;

        return $query;
    }

    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Add a document type to find.
     *
     * @param string $documentType
     * @return $this
     */
    public function addDocumentType($documentType)
    {
        if (!in_array($documentType, $this->_documentTypes))
        {
            $this->_documentTypes[] = $documentType;
        }

        return $this;
    }

    /**
     * Set document types to find.
     *
     * @param array $documentTypes
     * @return $this
     */
    public function setDocumentTypes(array $documentTypes)
    {
        $this->_documentTypes = $documentTypes;
        return $this;
    }

    /**
     * Get document types to find.
     *
     * @return array
     */
    public function getDocumentTypes()
    {
        return $this->_documentTypes;
    }
}