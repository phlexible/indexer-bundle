<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Query\Aggregation;

use Phlexible\Bundle\IndexerBundle\Query\Filter\FilterInterface;
use Phlexible\Bundle\IndexerBundle\Query\QueryParam;

/**
 * Abstract aggregation
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
abstract class AbstractAggregation extends QueryParam implements AggregationInterface
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var AggregationInterface
     */
    private $aggregations = array();

    /**
     * Constructor.
     *
     * @param string $name The name of the facet.
     */
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * {@inheritdoc}
     * @throws \InvalidArgumentException
     */
    public function setName($name)
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('Facet name has to be set');
        }

        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a filter for this facet.
     *
     * @param FilterInterface $filter
     *
     * @return $this
     */
    public function setFilter(FilterInterface $filter)
    {
        return $this->setParam('facet_filter', $filter);
    }

    /**
     * Add a sub-aggregation
     *
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
     * Return sub-aggregations
     *
     * @return AggregationInterface[]
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }
}
