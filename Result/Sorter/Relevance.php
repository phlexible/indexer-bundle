<?php
/**
 * Phlexible
 *
 * PHP Version 5
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */

/**
 * Relevance Sorter
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
class MWF_Core_Indexer_Result_Sorter_Relevance implements MWF_Core_Indexer_Result_Sorter_Interface
{
    /**
     * Sort documents
     *
     * @param array $documents
     * @return array
     */
    public function sort(array $documents)
    {
        usort($documents, array($this, '_compareRelevance'));

        return $documents;
    }

    protected function _compareRelevance(MWF_Core_Indexer_Document_Interface $a,
                                         MWF_Core_Indexer_Document_Interface $b)
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