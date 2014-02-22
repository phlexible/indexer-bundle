<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Indexer;

/**
 * Indexer collection
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class IndexerCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var IndexerInterface[]
     */
    protected $indexers = array();

    /**
     * @param IndexerInterface[] $indexers
     */
    public function __construct(array $indexers = array())
    {
        $this->indexers = $indexers;
    }

    /**
     * @param IndexerInterface $indexer
     * @return $this
     */
    public function addIndexer(IndexerInterface $indexer)
    {
        $this->indexers[] = $indexer;
        return $this;
    }

    /**
     * @return IndexerInterface[]
     */
    public function getIndexers()
    {
        return $this->indexers;
    }

    /**
     * @return IndexerInterface[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->indexers);
    }

    /**
     * @return integer
     */
    public function count()
    {
        return count($this->indexers);
    }
}
