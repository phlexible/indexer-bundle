<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Result;

use Phlexible\Bundle\IndexerBundle\Document\DocumentInterface;
use Phlexible\Bundle\IndexerBundle\Result\Sorter\SorterInterface;

/**
 * Indexer result
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class Result implements ResultInterface, \Countable, \ArrayAccess, \SeekableIterator
{
    /**
     * @var DocumentInterface[]
     */
    private $documents = array();

    /**
     * @var integer
     */
    private $current = 0;

    /**
     * @var int
     */
    private $total = 0;

    /**
     * @var int
     */
    private $start = 0;

    /**
     * @var int
     */
    private $rows = 0;

    /**
     * @var int
     */
    private $maxScore = 0;

    /**
     * {@inheritdoc}
     */
    public function add(DocumentInterface $document)
    {
        $this->documents[] = $document;

        return $this;
    }

    /**
     * @param DocumentInterface[] $documents
     *
     * @return $this
     */
    public function setDocuments(array $documents)
    {
        $this->removeAll();

        return $this->addDocuments($documents);
    }

    /**
     * {@inheritdoc}
     */
    public function addDocuments(array $documents = array())
    {
        foreach ($documents as $document) {
            $this->add($document);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * {@inheritdoc}
     */
    public function addResult(ResultInterface $result)
    {
        $this->addDocuments($result->getDocuments());

        return $this;
    }


    /**
     * @return $this
     */
    public function removeAll()
    {
        $this->documents = array();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        $a = array();

        foreach ($this->documents as $document) {
            $a[$document->getIdentifier()] = (string) $document;
        }

        return implode('', $a);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $a = array();

        foreach ($this->documents as $document) {
            $a[$document->getIdentifier()] = $document->toArray();
        }

        return $a;
    }

    /**
     * @param int $position
     */
    public function seek($position)
    {
        if ($this->offsetExists($position)) {
            $this->current = $position;
        }
    }

    /**
     * @return DocumentInterface
     */
    public function current()
    {
        return $this->documents[$this->current];
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return $this->current;
    }

    /**
     * Next
     */
    public function next()
    {
        ++$this->current;
    }

    /**
     * Rewind
     */
    public function rewind()
    {
        $this->current = 0;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->offsetExists($this->current);
    }

    /**
     * @param mixed $index
     *
     * @return bool
     */
    public function offsetExists($index)
    {
        if (!isset($this->documents[$index])) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param mixed $index
     *
     * @return mixed|null
     */
    public function offsetGet($index)
    {
        if ($this->offsetExists($index)) {
            return $this->documents[$index];
        }

        return null;
    }

    /**
     * @param mixed $index
     * @param mixed $newval
     */
    public function offsetSet($index, $newval)
    {
        $this->documents[$index] = $newval;
    }

    /**
     * @param mixed $index
     */
    public function offsetUnset($index)
    {
        unset($this->documents[$index]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->documents);
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param int $total
     *
     * @return $this
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param int $start
     *
     * @return $this
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return int
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param int $rows
     *
     * @return $this
     */
    public function setRows($rows)
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxScore()
    {
        return $this->maxScore;
    }

    /**
     * @param int $maxScore
     *
     * @return $this
     */
    public function setMaxScore($maxScore)
    {
        $this->maxScore = $maxScore;

        return $this;
    }
}