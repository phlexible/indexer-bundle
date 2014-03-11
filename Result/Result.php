<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Result;

use Phlexible\IndexerComponent\Document\DocumentInterface;
use Phlexible\IndexerComponent\Result\Sorter\SorterInterface;

/**
 * Indexer result
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class Result implements ResultInterface, \Countable, \ArrayAccess, \SeekableIterator
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
     * @var SorterInterface
     */
    protected $_sorter = null;

    /**
     * @param SorterInterface $sorter
     */
    public function __construct(SorterInterface $sorter)
    {
        $this->setSorter($sorter);
    }

    /**
     * {@inheritdoc}
     */
    public function addDocument(DocumentInterface $document)
    {
        $this->_documents[] = $document;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addDocuments(array $documents = array())
    {
        foreach ($documents as $document)
        {
            $this->addDocument($document);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addResult(ResultInterface $result)
    {
        $this->addDocuments($result->getResult());
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        return $this->_documents;
    }

    public function getPaginator($limit, $current = 0, $range = 5)
    {
        $paginator = \Zend_Paginator::factory($this->_documents);
        $paginator->setItemCountPerPage($limit);
        $paginator->setCurrentPageNumber($current);
        $paginator->setPageRange(5);

        return $paginator;
    }

    /**
     * {@inheritdoc}
     */
    public function setSorter(SorterInterface $sorter)
    {
        $this->_sorter = $sorter;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSorter()
    {
        return $this->_sorter;
    }

    /**
     * {@inheritdoc}
     */
    public function sort()
    {
        $this->_documents = $this->_sorter->sort($this->_documents);
        return $this;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
}