<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Query;

/**
 * Query parameter
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
abstract class QueryParam
{
    /**
     * @var array
     */
    private $params = array();

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    protected function setParam($key, $value)
    {
        $this->params[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    protected function addParam($key, $value)
    {
        if (!isset($this->params[$key])) {
            $this->params[$key] = array();
        }

        $this->params[$key][] = $value;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getParam($key)
    {
        return $this->params[$key];
    }

    /**
     * Sets (overwrites) all params of this object
     *
     * @param array $params
     *
     * @return $this
     */
    protected function setParams(array $params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}