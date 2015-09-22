<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Result;

use Phlexible\Bundle\IndexerBundle\Document\DocumentInterface;

/**
 * Result set
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class ResultSet implements \Countable, \ArrayAccess, \SeekableIterator
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
    private $totalHits = 0;

    /**
     * @var int
     */
    private $totalTime = 0;

    /**
     * @var int
     */
    private $start = 0;

    /**
     * @var int
     */
    private $limit = 0;

    /**
     * @var int
     */
    private $maxScore = 0;

    /**
     * Adds a document to the result set.
     *
     * @param \Phlexible\Bundle\IndexerBundle\Document\DocumentInterface $document
     *
     * @return $this
     */
    public function add(DocumentInterface $document)
    {
        $this->documents[(string) $document->getIdentity()] = $document;

        return $this;
    }

    /**
     * @param \Phlexible\Bundle\IndexerBundle\Document\DocumentInterface[] $documents
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
    public function addResultSet(ResultSet $results)
    {
        $this->addDocuments($results->getDocuments());

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
    public function all()
    {
        return $this->documents;
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
        return current($this->documents);
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return key($this->documents);
    }

    /**
     * Next
     */
    public function next()
    {
        next($this->documents);
    }

    /**
     * Rewind
     */
    public function rewind()
    {
        reset($this->documents);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return !!$this->key();
    }

    /**
     * @param mixed $index
     *
     * @return bool
     */
    public function offsetExists($index)
    {
        return isset($this->documents[$index]);
    }

    /**
     * @param mixed $index
     *
     * @return DocumentInterface|null
     */
    public function offsetGet($index)
    {
        if ($this->offsetExists($index)) {
            return $this->documents[$index];
        }

        return null;
    }

    /**
     * @param mixed             $index
     * @param DocumentInterface $document
     */
    public function offsetSet($index, $document)
    {
        $this->documents[$index] = $document;
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
    public function getTotalHits()
    {
        return $this->totalHits;
    }

    /**
     * @param int $totalHits
     *
     * @return $this
     */
    public function setTotalHits($totalHits)
    {
        $this->totalHits = $totalHits;

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
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     *
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

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

    /**
     * @return int
     */
    public function getTotalTime()
    {
        return $this->totalTime;
    }

    /**
     * @param int $totalTime
     *
     * @return $this
     */
    public function setTotalTime($totalTime)
    {
        $this->totalTime = $totalTime;

        return $this;
    }
}
