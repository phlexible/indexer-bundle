<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Result\Sorter;

use Phlexible\IndexerComponent\Document\DocumentInterface;

/**
 * Reverse relevance sorter
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class RelevanceReverseSorter extends RelevanceSorter
{
    protected function compare(DocumentInterface $a, DocumentInterface $b)
    {
	    return -1 * parent::compare($a, $b);
    }
}