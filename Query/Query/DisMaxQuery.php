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
class DisMaxQuery extends AbstractQuery
{
    /**
     * @var QueryInterface[]
     */
    private $queries = array();

    /**
     * Adds a query to the current object
     *
     * @param QueryInterface $query
     *
     * @return $this
     */
    public function addQuery(QueryInterface $query)
    {
        $this->queries[] = $query;
    }

    /**
     * @return QueryInterface[]
     */
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     * Set boost
     *
     * @param  float $boost
     *
     * @return $this
     */
    public function setBoost($boost)
    {
        return $this->setParam('boost', $boost);
    }

    /**
     * Sets tie breaker to multiplier value to balance the scores between lower and higher scoring fields.
     *
     * If not set, defaults to 0.0
     *
     * @param  float $tieBreaker
     *
     * @return $this
     */
    public function setTieBreaker($tieBreaker = 0.0)
    {
        return $this->setParam('tieBreaker', $tieBreaker);
    }
}
