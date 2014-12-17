<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Query\Query;

/**
 * Query string
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class FuzzyQuery extends AbstractQuery
{
    /**
     * Construct a fuzzy query
     *
     * @param  string $fieldName Field name
     * @param  string $value     String to search for
     */
    public function __construct ($fieldName = null, $value = null)
    {
        if ($fieldName && $value) {
            $this->setField($fieldName, $value);
        }
    }

    /**
     * Set field for fuzzy query
     *
     * @param string $fieldName Field name
     * @param string $value     String to search for
     *
     * @return $this
     */
    public function setField($fieldName, $value)
    {
        return $this->setParam('field', array('fieldName' => $fieldName, 'value' => $value, 'options' => array()));
    }

    /**
     * Set optional parameters on the existing query
     *
     * @param string $param Option name
     * @param mixed  $value Value of the parameter
     *
     * @return $this
     */
    public function setFieldOption($param, $value)
    {
        $field = $this->getParam('field');
        $field['options'][$param] = $value;

        return $this->setParam('field', $field);
    }
}
