<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Query\Filter;

/**
 * And filter
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class AndFilter implements FilterInterface
{
    /**
     * @var FilterInterface[]
     */
    private $filters;

    /**
     * @param FilterInterface[] $filters
     */
    public function __construct(array $filters = array())
    {
        $this->filters = $filters;
    }

    /**
     * @param FilterInterface $filter
     *
     * @return $this
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * @return FilterInterface[]
     */
    public function getFilters()
    {
        return $this->filters;
    }
}