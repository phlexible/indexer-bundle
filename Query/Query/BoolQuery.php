<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Query\Query;

/**
 * Query string
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class BoolQuery extends AbstractQuery
{
    /**
     * @var QueryInterface[]
     */
    private $queries = array();

    /**
     * @return QueryInterface[]
     */
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     * Add should part to query
     *
     * @param QueryInterface $query
     *
     * @return $this
     */
    public function addShould(QueryInterface $query)
    {
        return $this->addQuery('should', $query);
    }

    /**
     * Add must part to query
     *
     * @param QueryInterface $query
     *
     * @return $this
     */
    public function addMust(QueryInterface $query)
    {
        return $this->addQuery('must', $query);
    }

    /**
     * Add must not part to query
     *
     * @param QueryInterface $query
     *
     * @return $this
     */
    public function addMustNot(QueryInterface $query)
    {
        return $this->addQuery('mustNot', $query);
    }

    /**
     * Adds a query to the current object
     *
     * @param QueryInterface $query
     *
     * @return $this
     */
    private function addQuery($type, QueryInterface $query)
    {
        return $this->queries[$type][] = $query;
    }

    /**
     * Sets boost value of this query
     *
     * @param float $boost Boost value
     *
     * @return $this
     */
    public function setBoost($boost)
    {
        return $this->setParam('boost', $boost);
    }

    /**
     * Set the minimum number of of should match
     *
     * @param int $minimumNumberShouldMatch Should match minimum
     *
     * @return $this
     */
    public function setMinimumNumberShouldMatch($minimumNumberShouldMatch)
    {
        return $this->setParam('minimumNumberShouldMatch', $minimumNumberShouldMatch);
    }
}
