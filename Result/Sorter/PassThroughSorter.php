<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Result\Sorter;

/**
 * Pass through sorter
 *
 * @author Peter Fahsel <pfahsel@brainbits.net>
 */
class PassThrough implements SorterInterface
{
    /**
     * {@inheritdoc}
     */
    public function sort(array $documents)
    {
        return $documents;
    }
}