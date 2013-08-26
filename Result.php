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
 * Indexer Result
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
class MWF_Core_Indexer_Result
    implements MWF_Core_Indexer_Result_Interface, Countable, ArrayAccess, SeekableIterator
{
    /**
     * @var array
     */
    protected $_documents = array();

    /**
     * @var integer
     */
    protected $_current = 0;

    /**
     * @var MWF_Core_Indexer_Result_Sorter_Interface
     */
    protected $_sorter = null;

    /**
     * Constructor
     *
     * @param MWF_Core_Indexer_Result_Sorter_Interface $sorter
     */
    public function __construct(MWF_Core_Indexer_Result_Sorter_Interface $sorter)
    {
        $this->setSorter($sorter);
    }

    /**
     * Add a document to local documents.
     *
     * @param MWF_Core_Indexer_Document_Interface $document
     */
    public function addDocument(MWF_Core_Indexer_Document_Interface $document)
    {
        $this->_documents[] = $document;
    }

    /**
     * Merge documents to local docuemnt.
     *
     * @param array[MWF_Core_Indexer_Document_Interface] $documents
     */
    public function addDocuments(array $documents = array())
    {
        foreach ($documents as $document)
        {
            $this->addDocument($document);
        }
    }

    /**
     * Merge documents of a result to local documents.
     *
     * @param MWF_Core_Indexer_Result_Interface $result
     */
    public function addResult(MWF_Core_Indexer_Result_Interface $result)
    {
        $this->addDocuments($result->getResult());
    }

    /**
     * Return documents of this result
     *
     * @return array[MWF_Core_Indexer_Document_Interface]
     */
    public function getResult()
    {
        return $this->_documents;
    }

    public function getPaginator($limit, $current = 0, $range = 5)
    {
        $paginator = Zend_Paginator::factory($this->_documents);
        $paginator->setItemCountPerPage($limit);
        $paginator->setCurrentPageNumber($current);
        $paginator->setPageRange(5);

        return $paginator;
    }

    /**
     * Sort result documents by relevance
     */
    public function sort()
    {
        $this->_documents = $this->_sorter->sort($this->_documents);
    }

    /**
     * Render result to HTML by calling documents toHtml() method
     * @deprecated
     */
    public function toString()
    {
        $a = array();

        foreach ($this->_documents as $document)
        {
            $a[$document->getIdentifier()] = (string)$document;
        }

        return implode('', $a);
    }

    /**
     * @deprecated
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Render result to array by calling documents toArray() method
     * @deprecated
     */
    public function toArray()
    {
        $a = array();

        foreach ($this->_documents as $document)
        {
            $a[$document->getIdentifier()] = $document->toArray();
        }

        return $a;
    }

    public function seek($position)
    {
        if ($this->offsetExists($position))
        {
            $this->_current = $position;
        }
    }

    public function current()
    {
        return $this->_documents[$this->_current];
    }

    public function key()
    {
        return $this->_current;
    }

    public function next()
    {
        ++$this->_current;
    }

    public function rewind()
    {
        $this->_current = 0;
    }

    public function valid()
    {
        return $this->offsetExists($this->_current);
    }

    public function offsetExists($index)
    {
        if (!isset($this->_documents[$index]))
        {
            return false;
        } else
        {
            return true;
        }
    }

    public function offsetGet($index)
    {
        if ($this->offsetExists($index))
        {
            return $this->_documents[$index];
        } else
        {
            return null;
        }
    }

    public function offsetSet($index, $newval)
    {
        $this->_documents[$index] = $newval;
    }

    public function offsetUnset($index)
    {
        unset($this->_documents[$index]);
    }

    public function count()
    {
        return count($this->_documents);
    }

    public function clear()
    {
        foreach (array_keys($this->_documents) as $index)
        {
            unset($this->_documents[$index]);
        }
    }

    /**
     * @param MWF_Core_Indexer_Result_Sorter_Interface $sorter
     */
    public function setSorter(MWF_Core_Indexer_Result_Sorter_Interface $sorter)
    {
        $this->_sorter = $sorter;
    }
}