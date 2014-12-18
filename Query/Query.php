<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Query;

use Phlexible\Bundle\IndexerBundle\Query\Aggregation\AggregationInterface;
use Phlexible\Bundle\IndexerBundle\Query\Facet\FacetInterface;
use Phlexible\Bundle\IndexerBundle\Query\Filter\FilterInterface;
use Phlexible\Bundle\IndexerBundle\Query\Query\QueryInterface;
use Phlexible\Bundle\IndexerBundle\Query\Suggest\SuggestInterface;

/**
 * Query
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class Query
{
    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @var FilterInterface
     */
    private $filter;

    /**
     * @var FacetInterface[]
     */
    private $facets = array();

    /**
     * @var AggregationInterface[]
     */
    private $aggregations = array();

    /**
     * @var SuggestInterface
     */
    private $suggest;

    /**
     * @var int
     */
    private $size = 100;

    /**
     * @var int
     */
    private $start = 0;

    /**
     * @var array
     */
    private $sort = array();

    /**
     * @var array
     */
    private $highlight = array();

    /**
     * @var int
     */
    private $minScore = 0;

    /**
     * @var bool
     */
    private $explain;

    /**
     * @var array
     */
    private $fields = array();

    /**
     * @var int
     */
    private $version;

    /**
     * @var array|false
     */
    private $source = false;

    /**
     * Constructor.
     */
    public function __construct()
    {
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
     * @param FacetInterface[] $facets
     *
     * @return $this
     */
    public function setFacets(array $facets = array())
    {
        $this->facets = $facets;

        return $this;
    }

    /**
     * @return FacetInterface
     */
    public function getFacets()
    {
        return $this->facets;
    }

    /**
     * @param AggregationInterface $aggregation
     *
     * @return $this
     */
    public function addAggregation(AggregationInterface $aggregation)
    {
        $this->aggregations[] = $aggregation;

        return $this;
    }

    /**
     * @param AggregationInterface[] $aggregations
     *
     * @return $this
     */
    public function setAggregations(array $aggregations = array())
    {
        $this->aggregations = $aggregations;

        return $this;
    }

    /**
     * @return AggregationInterface
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * @param Suggest $suggest
     *
     * @return $this
     */
    public function setSuggest(Suggest $suggest)
    {
        $this->suggest = $suggest;

        return $this;
    }

    /**
     * @return Suggest
     */
    public function getSuggest()
    {
        return $this->suggest;
    }

    /**
     * @param int $size
     *
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
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
     * @return int
     */
    public function getStart()
    {
        return $this->start;
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
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return array
     */
    public function getHighlight()
    {
        return $this->highlight;
    }

    /**
     * @param array $highlight
     *
     * @return $this
     */
    public function setHighlight(array $highlight)
    {
        $this->highlight = $highlight;

        return $this;
    }

    /**
     * @return int
     */
    public function getMinScore()
    {
        return $this->minScore;
    }

    /**
     * @param int $minScore
     *
     * @return $this
     */
    public function setMinScore($minScore)
    {
        $this->minScore = $minScore;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getExplain()
    {
        return $this->explain;
    }

    /**
     * @param boolean $explain
     *
     * @return $this
     */
    public function setExplain($explain)
    {
        $this->explain = $explain;

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
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param int $version
     *
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return array|false
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param array $source
     *
     * @return $this
     */
    public function setSource(array $source)
    {
        $this->source = $source;

        return $this;
    }
}