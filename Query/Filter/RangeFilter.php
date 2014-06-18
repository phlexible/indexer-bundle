<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Query\Filter;

/**
 * Range filter
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class RangeFilter implements FilterInterface
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var int
     */
    private $from = '*';

    /**
     * @var int
     */
    private $to = '*';

    /**
     * @param string $field
     * @param int    $from
     * @param int    $to
     */
    public function __construct($field = null, $from = null, $to = null)
    {
        $this->field = $field;
        $this->from = $from;
        $this->to = $to;
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
     * @param int $from
     *
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return int
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param int $to
     *
     * @return $this
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return int
     */
    public function getTo()
    {
        return $this->to;
    }
}