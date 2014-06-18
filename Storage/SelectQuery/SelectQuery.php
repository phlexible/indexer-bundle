<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Storage\SelectQuery;

use Phlexible\IndexerBundle\Boost\BoostInterface;
use Phlexible\IndexerBundle\Query\Facet\FacetInterface;
use Phlexible\IndexerBundle\Query\Filter\FilterInterface;
use Phlexible\IndexerBundle\Query\Query\QueryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Select query
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class SelectQuery
{
    /**
      * @var EventDispatcherInterface
      */
    protected $dispatcher;

    /**
     * @var array
     */
    private $documentTypes = array();

    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @var BoostInterface
     */
    private $boost;

    /**
     * @var array
     */
    private $fields = array();

    /**
     * @var FilterInterface
     */
    private $filter;

    /**
     * @var FacetInterface[]
     */
    private $facets = array();

    /**
     * @var int
     */
    private $rows;

    /**
     * @var int
     */
    private $start;

    /**
     * @var array
     */
    private $sort = array();

    /**
     * @var bool
     */
    private $highlight = false;

    /**
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param string $field
     *
     * @return $this
     */
    public function addField($field)
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * @param array $fields
     *
     * @return $this
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }
    /**
     * @param BoostInterface|null $boost
     *
     * @return $this
     */
    public function setBoost(BoostInterface $boost)
    {
        $this->boost = $boost;

        return $this;
    }

    /**
     * @return BoostInterface
     */
    public function getBoost()
    {
        return $this->boost;
    }

    /**
     * Set filter
     *
     * @param FilterInterface $filter
     *
     * @return $this
     */
    public function setFilter(FilterInterface $filter)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * Return filter
     *
     * @return FilterInterface
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param FacetInterface $facet
     *
     * @return $this
     */
    public function addFacet(FacetInterface $facet)
    {
        $this->facets[] = $facet;

        return $this;
    }

    /**
     * @return FacetInterface[]
     */
    public function getFacets()
    {
        return $this->facets;
    }

    /**
     * Set document types to find.
     *
     * @param array $documentTypes
     *
     * @return $this
     */
    public function setDocumentTypes(array $documentTypes)
    {
        $this->documentTypes = $documentTypes;

        return $this;
    }

    /**
     * Get document types to find.
     *
     * @return array
     */
    public function getDocumentTypes()
    {
        return $this->documentTypes;
    }

    /**
     * Add a document type to find.
     *
     * @param string $documentType
     *
     * @return $this
     */
    public function addDocumentType($documentType)
    {
        if (!in_array($documentType, $this->documentTypes)) {
            $this->documentTypes[] = $documentType;
        }

        return $this;
    }

    /**
     * @param QueryInterface $query
     *
     * @return $this
     */
    public function setQuery(QueryInterface $query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @return QueryInterface
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function toString()
    {
        $query = 'Terms: ';

        $terms = $this->getParser()->parse($input);

        if (count($terms->getPositiveTerms())) {
            $query .= '+';
        }
        $query .= implode(',+', $terms->getPositiveTerms());

        if (count($terms->getNegativeTerms())) {
            $query .= '-';
        }
        $query .= implode(',-', $terms->getNegativeTerms());

        $query .= PHP_EOL;

        if (count($this->getFilters())) {
            $query .= 'Filters: ' . PHP_EOL . print_r($this->getFilters(), 1);

            //            foreach ($this->getFilters() as $filterkey => $filterValue)
            //            {
            //                $query .= "$filterkey: ";
            //                $query .= implode('|', (array) $filterValue);
            //                $query .= ', ';
            //            }
            //            $query = substr($query, 0, -2);
        }
        $query .= implode(',-', $terms->getNegativeTerms());

        $query .= PHP_EOL;

        $query .= implode(',-', $terms->getNegativeTerms());
        $query .= 'Document types: ' . implode(', ', $this->getDocumentTypes()) . PHP_EOL;
        $query .= 'Fields: ' . implode(', ', $this->getFields()) . PHP_EOL;

        return $query;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @return int
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param int $size
     *
     * @return $this
     */
    public function setRows($size)
    {
        $this->rows = $size;

        return $this;
    }

    /**
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param int $start
     *
     * @return $this
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return array
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param array $sort
     *
     * @return $this
     */
    public function setSort(array $sort)
    {
        foreach ($sort as $field => $dir) {
            $this->addSort($field, $dir);
        }

        return $this;
    }

    /**
     * @param string $field
     * @param string $dir
     *
     * @return $this
     */
    public function addSort($field, $dir)
    {
        $this->sort[$field] = $dir;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getHighlight()
    {
        return $this->highlight;
    }

    /**
     * @param boolean $highlight
     *
     * @return $this
     */
    public function setHighlight($highlight)
    {
        $this->highlight = $highlight;

        return $this;
    }
}
