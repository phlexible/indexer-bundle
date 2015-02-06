<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Storage;

/**
 * Optimizable interface
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
interface Optimizable
{
    /**
     * Optimize index
     */
    public function optimize();
}