<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Query\Filter;

/**
 * Term filter
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class TermFilter extends AbstractFilter
{
    /**
     * Construct term filter
     *
     * @param array $term Term array
     */
    public function __construct(array $term = array())
    {
        $this->setRawTerm($term);
    }

    /**
     * Sets/overwrites key and term directly
     *
     * @param array $term Key value pair
     *
     * @return $this
     */
    public function setRawTerm(array $term)
    {
        return $this->setParam('rawTerm', $term);
    }

    /**
     * Adds a term to the term query
     *
     * @param string       $key   Key to query
     * @param string|array $value Values(s) for the query. Boost can be set with array
     *
     * @return $this
     */
    public function setTerm($key, $value)
    {
        return $this->setRawTerm(array($key => $value));
    }
}