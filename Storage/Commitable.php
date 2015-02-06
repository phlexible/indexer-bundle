<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Storage;

/**
 * Commitable interface
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
interface Commitable
{
    /**
     * Commit index
     */
    public function commit();
}