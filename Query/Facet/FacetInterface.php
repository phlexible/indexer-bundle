<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Query\Facet;

/**
 * Facet interface
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
interface FacetInterface
{
    /**
     * Sets the name of the facet. It is automatically set by
     * the constructor.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);

    /**
     * Gets the name of the facet.
     *
     * @return string
     */
    public function getName();
}