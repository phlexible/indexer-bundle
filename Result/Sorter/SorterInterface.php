<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Result\Sorter;

/**
 * Result sorter interface
 *
 * @author Marco Fischer <mf@brainbits.net>
 * @author Phillip Look <pl@brainbits.net>
 */
interface SorterInterface
{
    const LIST_A_FIRST = -1;
    const LIST_B_FIRST = 1;
    const EQUALS = 0;

    /**
     * Sort documents
     *
     * @param array $documents
     * @return array
     */
    public function sort(array $documents);
}