<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Storage;

/**
 * Flushable interface
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
interface Flushable
{
    /**
     * Flush index
     */
    public function flush();
}