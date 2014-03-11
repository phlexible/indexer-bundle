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
 * Relevance sorter
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class RelevanceSorter implements SorterInterface
{
    /**
     * {@inheritdoc}
     */
    public function sort(array $documents)
    {
        usort($documents, array($this, 'compareRelevance'));

        return $documents;
    }

    protected function compareRelevance(DocumentInterface $a, DocumentInterface $b)
    {
        $relA = $a->getRelevance();
        $relB = $b->getRelevance();

        if ($relA == $relB)
        {
            return self::EQUALS;
        }

        return ($relA > $relB)
            ? self::LIST_A_FIRST
            : self::LIST_B_FIRST;
    }
}