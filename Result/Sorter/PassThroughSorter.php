<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Result\Sorter;

/**
 * Pass through sorter
 *
 * @author Peter Fahsel <pfahsel@brainbits.net>
 */
class PassThrough implements SorterInterface
{
    /**
     * @inheritDoc
     */
    public function sort(array $documents)
    {
        return $documents;
    }
}