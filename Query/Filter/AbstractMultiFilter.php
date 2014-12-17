<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Query\Filter;

/**
 * Abstract multi filter
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
abstract class AbstractMultiFilter extends AbstractFilter
{
    /**
     * @var array
     */
    private $filters = array();

    /**
     * @param FilterInterface[] $filters
     */
    public function __construct(array $filters = array())
    {
        $this->setFilters($filters);
    }

    /**
     * Add filter
     *
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
     * Set filters
     *
     * @param array $filters
     *
     * @return $this
     */
    public function setFilters(array $filters)
    {
        $this->filters = array();

        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }

        return $this;
    }

    /**
     * @return array Filters
     */
    public function getFilters()
    {
        return $this->filters;
    }
}
