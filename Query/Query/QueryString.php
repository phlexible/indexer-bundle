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
class QueryString extends AbstractQuery
{
    /**
     * @param string $queryString
     */
    public function __construct($queryString)
    {
        $this->setQueryString($queryString);
    }

    /**
     * @param string $queryString
     *
     * @return string
     */
    public function setQueryString($queryString)
    {
        $this->setParam('query', $queryString);

        return $this;
    }

    /**
     * Sets the default field
     *
     * @param string $field
     *
     * @return $this
     */
    public function setDefaultField($field)
    {
        return $this->setParam('defaultField', $field);
    }

    /**
     * Sets the default operator AND or OR
     *
     * If no operator is set, OR is chosen
     *
     * @param string $operator
     *
     * @return $this
     */
    public function setDefaultOperator($operator)
    {
        return $this->setParam('defaultOperator', $operator);
    }
}