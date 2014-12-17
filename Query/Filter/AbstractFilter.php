<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Query\Filter;

use Phlexible\Bundle\IndexerBundle\Query\QueryParam;

/**
 * Filter interface
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
abstract class AbstractFilter extends QueryParam implements FilterInterface
{
    /**
     * Sets the filter cache
     *
     * @param boolean $cached
     *
     * @return $this
     */
    public function setCached($cached = true)
    {
        return $this->setParam('_cache', (bool) $cached);
    }

    /**
     * Sets the filter cache key
     *
     * @param string $cacheKey
     *
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setCacheKey($cacheKey)
    {
        $cacheKey = (string) $cacheKey;

        if (empty($cacheKey)) {
            throw new \InvalidArgumentException('Invalid parameter. Has to be a non empty string');
        }

        return $this->setParam('_cache_key', $cacheKey);
    }

    /**
     * Sets the filter name
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        return $this->setParam('_name', $name);
    }
}
