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
class MultiMatchQuery extends AbstractQuery
{
    const TYPE_BEST_FIELDS   = 'best_fields';
    const TYPE_MOST_FIELDS   = 'most_fields';
    const TYPE_CROSS_FIELDS  = 'cross_fields';
    const TYPE_PHRASE        = 'phrase';
    const TYPE_PHRASE_PREFIX = 'phrase_prefix';

    const OPERATOR_OR        = 'or';
    const OPERATOR_AND       = 'and';

    const ZERO_TERM_NONE     = 'none';
    const ZERO_TERM_ALL      = 'all';

    /**
     * Sets the query
     *
     * @param  string $query Query
     *
     * @return $this
     */
    public function setQuery($query = '')
    {
        return $this->setParam('query', $query);
    }

    /**
     * Sets Fields to be used in the query.
     *
     * @param array $fields Fields
     *
     * @return $this
     */
    public function setFields($fields = array())
    {
        return $this->setParam('fields', $fields);
    }

    /**
     * Sets use dis max indicating to either create a dis_max query or a bool query.
     *
     * If not set, defaults to true.
     *
     * @param boolean $useDisMax
     *
     * @return $this
     */
    public function setUseDisMax($useDisMax = true)
    {
        return $this->setParam('useDisMax', $useDisMax);
    }

    /**
     * Sets tie breaker to multiplier value to balance the scores between lower and higher scoring fields.
     *
     * If not set, defaults to 0.0.
     *
     * @param float $tieBreaker
     *
     * @return $this
     */
    public function setTieBreaker($tieBreaker = 0.0)
    {
        return $this->setParam('tieBreaker', $tieBreaker);
    }

    /**
     * Sets operator for Match Query
     *
     * If not set, defaults to 'or'
     *
     * @param string $operator
     *
     * @return $this
     */
    public function setOperator($operator = 'or')
    {
        return $this->setParam('operator', $operator);
    }

    /**
     * Set field minimum should match for Match Query
     *
     * @param int $minimumShouldMatch
     *
     * @return $this
     */
    public function setMinimumShouldMatch($minimumShouldMatch)
    {
        return $this->setParam('minimumShouldMatch', (int) $minimumShouldMatch);
    }

    /**
     * Set zero terms query for Match Query
     * If not set, default to 'none'
     *
     * @param string $zeroTermQuery
     *
     * @return $this
     */
    public function setZeroTermsQuery($zeroTermQuery = 'none')
    {
        return $this->setParam('zeroTermsQuery', $zeroTermQuery);
    }

    /**
     * Set cutoff frequency for Match Query
     *
     * @param float $cutoffFrequency
     *
     * @return $this
     */
    public function setCutoffFrequency($cutoffFrequency)
    {
        return $this->setParam('cutoffFrequency', $cutoffFrequency);
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        return $this->setParam('type', $type);
    }

    /**
     * Set fuzziness
     *
     * @param float $fuzziness
     *
     * @return $this
     */
    public function setFuzziness($fuzziness)
    {
        return $this->setParam('fuzziness', (float) $fuzziness);
    }

    /**
     * Set prefix length
     *
     * @param int $prefixLength
     *
     * @return $this
     */
    public function setPrefixLength($prefixLength)
    {
        return $this->setParam('prefixLength', (int) $prefixLength);
    }

    /**
     * Set max expansions
     *
     * @param int $maxExpansions
     *
     * @return $this
     */
    public function setMaxExpansions($maxExpansions)
    {
        return $this->setParam('maxExpansions', (int) $maxExpansions);
    }

    /**
     * Set analyzer
     *
     * @param string $analyzer
     *
     * @return $this
     */
    public function setAnalyzer($analyzer)
    {
        return $this->setParam('analyzer', $analyzer);
    }
}
