<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Query\Filter;

/**
 * Range filter
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class RangeFilter extends AbstractFilter
{
    /**
     * Construct range filter
     *
     * @param string|bool $fieldName Field name
     * @param array       $args      Field arguments
     */
    public function __construct($fieldName = false, array $args = array())
    {
        if ($fieldName) {
            $this->addField($fieldName, $args);
        }
    }

    /**
     * Ads a field with arguments to the range query
     *
     * @param string $fieldName Field name
     * @param array  $args      Field arguments
     *
     * @return $this
     */
    public function addField($fieldName, array $args)
    {
        return $this->setParam('fields', array($fieldName => $args));
    }
}