<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Indexer;

use Phlexible\Bundle\IndexerBundle\Document\DocumentInterface;
use Phlexible\Bundle\IndexerBundle\Storage\StorageInterface;

/**
 * Indexer interface
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
interface IndexerInterface
{
    /**
     * Return name
     *
     * @return string
     */
    public function getName();

    /**
     * Return type
     *
     * @return string
     */
    public function getType();

    /**
     * Return associated storage
     *
     * @return StorageInterface
     */
    public function getStorage();

    /**
     * @param string|DocumentInterface $identifier
     *
     * @return bool
     */
    public function supports($identifier);

    /**
     * Index document identified by identifier
     *
     * @param string $identifier
     *
     * @return bool
     */
    public function add($identifier);

    /**
     * Index document identified by identifier
     *
     * @param string $identifier
     *
     * @return bool
     */
    public function update($identifier);

    /**
     * Index all documents
     *
     * @param bool $viaQueue
     *
     * @return bool
     */
    public function indexAll($viaQueue = false);
}