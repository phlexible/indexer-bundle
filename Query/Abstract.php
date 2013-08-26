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
 * Query
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
abstract class MWF_Core_Indexer_Query_Abstract implements MWF_Core_Indexer_Query_Interface
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
     * @var MWF_Core_Indexer_Boost_Interface
     */
    protected $_boost;

    /**
     * @var MWF_Core_Indexer_Query_Parser_Interface
     */
    protected $_parser;

    /**
     * Constructor
     *
     * @param MWF_Core_Indexer_Query_Parser   $parser
     * @param MWF_Core_Indexer_Boost_Abstract $boost (optional)
     */
    public function __construct(MWF_Core_Indexer_Query_Parser_Interface $parser,
                                MWF_Core_Indexer_Boost_Interface        $boost = null)
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

    public function setBoost(MWF_Core_Indexer_Boost_Interface $boost)
    {
        $this->_boost = $boost;
    }

    /**
     * @return MWF_Core_Indexer_Boost_Interface
     */
    public function getBoost()
    {
        return $this->_boost;
    }

    /**
     * Set parser
     *
     * @param MWF_Core_Indexer_Query_Parser_Interface $parser
     */
    public function setParser(MWF_Core_Indexer_Query_Parser_Interface $parser)
    {
        $this->_parser = $parser;
    }

    /**
     * Add a filter
     *
     * @param array $filters
     */
    public function addFilter($key, $value)
    {
        if (null === $this->_filters)
        {
            $this->_filters = array();
        }

        $this->_filters[$key] = $value;
    }

    /**
     * Set filters
     *
     * @param array $filters
     */
    public function setFilters(array $filters)
    {
        $this->_filters = $filters;
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
     * @param string $filters
     *
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
     * @return boolean
     */
    public function hasFilter($key)
    {
        return isset($this->_filters[$key]);
    }

    /**
     * Get parser
     *
     * @return MWF_Core_Indexer_Query_Parser_Interface
     */
    public function getParser()
    {
        return $this->_parser;
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
            throw new MWF_Core_Indexer_Exception('Define label in concrete class.');
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
     * @param sting $interface
     */
    public function addDocumentType($documentType)
    {
        if (!in_array($documentType, $this->_documentTypes))
        {
            $this->_documentTypes[] = $documentType;
        }
    }

    /**
     * Set document types to find.
     *
     * @param array $documentTypes
     */
    public function setDocumentTypes(array $documentTypes)
    {
        return $this->_documentTypes = $documentTypes;
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