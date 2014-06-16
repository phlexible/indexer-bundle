<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Storage;
use Traversable;

/**
 * Storage collection
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class StorageCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var Storage[]
     */
    protected $storages = array();

    /**
     * @param Storage[] $storages
     */
    public function __construct(array $storages = array())
    {
        $this->storages = $storages;
    }

    /**
     * @param Storage $storage
     * @return $this
     */
    public function addStorage(Storage $storage)
    {
        $this->storages[] = $storage;
        return $this;
    }

    /**
     * @return Storage[]
     */
    public function getStorages()
    {
        return $this->storages;
    }

    /**
     * @return Storage[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->storages);
    }

    /**
     * @return integer
     */
    public function count()
    {
        return count($this->storages);
    }
}
