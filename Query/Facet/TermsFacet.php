<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Query\Facet;

/**
 * Term facet
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class TermsFacet extends AbstractFacet
{
    /**
     * Sets the field for the terms.
     *
     * @param string $field
     *
     * @return $this
     */
    public function setField($field)
    {
        return $this->setParam('field', $field);
    }

    /**
     * Sets multiple fields for the terms.
     *
     * @param array $fields
     *
     * @return $this
     */
    public function setFields(array $fields)
    {
        return $this->setParam('fields', $fields);
    }

    /**
     * Sets the flag to return all available terms. When they
     * don't have a hit, they have a count of zero.
     *
     * @param bool $allTerms
     *
     * @return $this
     */
    public function setAllTerms($allTerms)
    {
        return $this->setParam('allTerms', (bool) $allTerms);
    }

    /**
     * Sets the ordering type for this facet. Default is count.
     *
     * @param string $type The order type to set use for sorting of the terms.
     *
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setOrder($type)
    {
        if (!in_array($type, array('count', 'term', 'reverse_count', 'reverse_term'))) {
            throw new \InvalidArgumentException('Invalid order type: ' . $type);
        }

        return $this->setParam('order', $type);
    }

    /**
     * Set an array with terms which are omitted in the search.
     *
     * @param array $exclude
     *
     * @return $this
     */
    public function setExclude(array $exclude)
    {
        return $this->setParam('exclude', $exclude);
    }

    /**
     * Sets the amount of terms to be returned.
     *
     * @param int $size
     *
     * @return $this
     */
    public function setSize($size)
    {
        return $this->setParam('size', (int) $size);
    }
}