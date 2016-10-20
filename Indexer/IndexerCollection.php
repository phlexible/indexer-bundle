<?php

/*
 * This file is part of the phlexible indexer package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\IndexerBundle\Indexer;

use Phlexible\Bundle\IndexerBundle\Document\DocumentIdentity;

/**
 * Indexer collection.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class IndexerCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var IndexerInterface[]
     */
    private $indexers = array();

    /**
     * @param IndexerInterface[] $indexers
     */
    public function __construct(array $indexers = array())
    {
        foreach ($indexers as $indexer) {
            $this->addIndexer($indexer);
        }
    }

    /**
     * @param IndexerInterface $indexer
     *
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
    public function all()
    {
        return $this->indexers;
    }

    /**
     * @param DocumentIdentity $identity
     *
     * @return bool
     */
    public function supports(DocumentIdentity $identity)
    {
        foreach ($this->indexers as $indexer) {
            if ($indexer->supports($identity)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return IndexerInterface[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->indexers);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->indexers);
    }
}
