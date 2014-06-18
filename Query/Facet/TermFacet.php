<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Query\Facet;

/**
 * Term facet
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class TermFacet implements FacetInterface
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var int
     */
    private $size;

    /**
     * @var string
     */
    private $order;

    /**
     * @param string $field
     */
    public function __construct($field = null)
    {
        $this->field = $field;
    }

    /**
     * @param string $field
     *
     * @return $this
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @param int $size
     *
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param string $order
     *
     * @return $this
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }
}