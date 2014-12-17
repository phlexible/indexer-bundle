<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Query\Filter;

/**
 * Terms filter
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class TermsFilter extends AbstractFilter
{
    /**
     * Creates terms filter
     *
     * @param string $key   Terms key
     * @param array  $terms Terms values
     */
    public function __construct($key = '', array $terms = array())
    {
        $this->setTerms($key, $terms);
    }

    /**
     * Sets key and terms for the filter
     *
     * @param string $key   Terms key
     * @param array  $terms Terms for the query.
     *
     * @return $this
     */
    public function setTerms($key, array $terms)
    {
        return $this->setParam('terms', array($key => array_values($terms)));
    }

    /**
     * Set the lookup parameters for this filter
     *
     * @param string       $key     terms key
     * @param string       $type    document type from which to fetch the terms values
     * @param string       $id      Id of the document from which to fetch the terms values
     * @param string       $path    The field from which to fetch the values for the filter
     * @param string|array $options An array of options or the index from which to fetch the terms values. Defaults to the current index.
     *
     * @return $this
     */
    public function setLookup($key, $type, $id, $path, $options = array())
    {
        $terms = array(
            'type' => $type,
            'id'   => $id,
            'path' => $path
        );

        $index = $options;
        if (is_array($options)) {
            if (isset($options['index'])) {
                $index = $options['index'];
                unset($options['index']);
            }
            $terms = array_merge($options, $terms);
        }

        if (!is_null($index)) {
            $terms['index'] = $index;
        }

        $this->setTerms($key, $terms);

        return $this;
    }
}
