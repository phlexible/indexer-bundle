<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Query\Filter;

/**
 * Term filter
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class TermFilter implements FilterInterface
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $term;

    /**
     * @param string $field
     * @param mixed  $term
     */
    public function __construct($field = null, $term = null)
    {
        $this->field = $field;
        $this->term = $term;
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
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param string $term
     *
     * @return $this
     */
    public function setTerm($term)
    {
        $this->term = $term;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->term;
    }
}