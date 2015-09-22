<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Indexer;

use Phlexible\Bundle\IndexerBundle\Document\DocumentIdentity;

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
     * @return integer
     */
    public function count()
    {
        return count($this->indexers);
    }
}
