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
 * Field Sorter
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Phillip Look <pl@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
class MWF_Core_Indexer_Result_Sorter_Field extends MWF_Core_Indexer_Result_Sorter_Relevance
{
    /**
     * @var string
     */
    protected $_compareField;
    
    /**
     * @var boolean
     */
    protected $_caseSensitive = true;

    /**
     * @param string  $compareField
     * @param boolean $caseSensitive
     */
    public function __construct($compareField, $caseSensitive = true)
    {
        $this->_compareField  = (string) $compareField;
        $this->_caseSensitive = $caseSensitive;
    }

    /**
     * Sort documents
     *
     * @param array $documents
     * @return array
     */
    public function sort(array $documents)
    {
        usort($documents, array($this, '_compare'));

        return $documents;
    }

    protected function _compare(MWF_Core_Indexer_Document_Interface $a,
                                MWF_Core_Indexer_Document_Interface $b)
    {
        $aHasField = $a->hasField($this->_compareField);
		$bHasField = $b->hasField($this->_compareField);
		
		if ($aHasField && $bHasField)
        {
            return $this->_compareValues($a, $b);
        }
        elseif (!$aHasField && !$bHasField)
        {
            return $this->_compareRelevance($a, $b);
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

    protected function _compareValues(MWF_Core_Indexer_Document_Interface $a,
                                      MWF_Core_Indexer_Document_Interface $b)
    {
        // compare values
        $aValue = $a->getValue($this->_compareField);
        $bValue = $b->getValue($this->_compareField);

        if ($this->_caseSensitive)
        {
            $natcmpResult = strnatcmp($aValue, $bValue);
        }
        else
        {
            $natcmpResult = strnatcasecmp($aValue, $bValue);
        }

        if (!$natcmpResult)
        {
            return $this->_compareRelevance($a, $b);
        }

        return $natcmpResult;
    }
}