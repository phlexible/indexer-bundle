<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Result\Sorter;
use Phlexible\Bundle\IndexerBundle\Document\DocumentInterface;

/**
 * Field sorter
 *
 * @author Phillip Look <pl@brainbits.net>
 */
class FieldSorter extends RelevanceSorter
{
    /**
     * @var string
     */
    protected $compareField;

    /**
     * @var boolean
     */
    protected $caseSensitive = true;

    /**
     * @param string  $compareField
     * @param boolean $caseSensitive
     */
    public function __construct($compareField, $caseSensitive = true)
    {
        $this->compareField  = (string)$compareField;
        $this->caseSensitive = $caseSensitive;
    }

    /**
     * {@inheritdoc}
     */
    public function sort(array $documents)
    {
        usort($documents, array($this, 'compare'));

        return $documents;
    }

    protected function compare(DocumentInterface $a, DocumentInterface $b)
    {
        $aHasField = $a->hasField($this->compareField);
		$bHasField = $b->hasField($this->compareField);

		if ($aHasField && $bHasField)
        {
            return $this->compareValues($a, $b);
        }
        elseif (!$aHasField && !$bHasField)
        {
            return $this->compareRelevance($a, $b);
        }
        elseif ($aHasField)
        {
            // a has value, b not
            return self::LIST_A_FIRST;
        }
        else
        {
            // b has value, a not
            return self::LIST_B_FIRST;
        }
    }

    protected function compareValues(DocumentInterface $a, DocumentInterface $b)
    {
        // compare values
        $aValue = $a->getValue($this->compareField);
        $bValue = $b->getValue($this->compareField);

        if ($this->caseSensitive)
        {
            $natcmpResult = strnatcmp($aValue, $bValue);
        }
        else
        {
            $natcmpResult = strnatcasecmp($aValue, $bValue);
        }

        if (!$natcmpResult)
        {
            return $this->compareRelevance($a, $b);
        }

        return $natcmpResult;
    }
}