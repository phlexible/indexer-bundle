<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Query\Facet;

use Phlexible\Bundle\IndexerBundle\Query\Filter\FilterInterface;
use Phlexible\Bundle\IndexerBundle\Query\QueryParam;

/**
 * Abstract facet
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
abstract class AbstractFacet extends QueryParam implements FacetInterface
{
    /**
     * @var string
     */
    private $name = '';

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
     * Sets the flag to either run the facet globally or bound to the
     * current search query. When not set, it defaults to the
     * Elasticsearch default value.
     *
     * @param bool $global
     *
     * @return $this
     */
    public function setGlobal($global = true)
    {
        return $this->setParam('global', (bool) $global);
    }

    /**
     * Sets the path to the nested document
     *
     * @param string $nestedPath
     *
     * @return $this
     */
    public function setNested($nestedPath)
    {
        return $this->setParam('nested', $nestedPath);
    }

    /**
     * Sets the scope
     *
     * @param string $scope Scope
     *
     * @return $this
     */
    public function setScope($scope)
    {
        return $this->setParam('scope', $scope);
    }
}
