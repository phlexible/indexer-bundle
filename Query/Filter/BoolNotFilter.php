<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Query\Filter;

/**
 * Bool not filter
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class BoolNotFilter extends AbstractFilter
{
    /**
     * Creates not filter query
     *
     * @param FilterInterface $filter
     */
    public function __construct(FilterInterface $filter)
    {
        $this->setFilter($filter);
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
        return $this->setParam('filter', $filter);
    }
}
