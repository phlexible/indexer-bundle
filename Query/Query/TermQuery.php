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
class TermQuery extends AbstractQuery
{
    /**
     * Constructs the Term query object
     *
     * @param array $term OPTIONAL Calls setTerm with the given $term array
     */
    public function __construct(array $term = array())
    {
        $this->setRawTerm($term);
    }

    /**
     * Set term can be used instead of addTerm if some more special
     * values for a term have to be set.
     *
     * @param  array $term Term array
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
     * @param  string       $key   Key to query
     * @param  string|array $value Values(s) for the query. Boost can be set with array
     * @param  float        $boost OPTIONAL Boost value (default = 1.0)
     *
     * @return $this
     */
    public function setTerm($key, $value, $boost = 1.0)
    {
        return $this->setRawTerm(array($key => array('value' => $value, 'boost' => $boost)));
    }
}
