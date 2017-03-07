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
use Phlexible\Bundle\IndexerBundle\Storage\StorageInterface;

/**
 * Indexer interface.
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
interface IndexerInterface
{
    /**
     * Return type.
     *
     * @return string
     */
    public function getType();

    /**
     * Return associated storage.
     *
     * @return StorageInterface
     */
    public function getStorage();

    /**
     * @param DocumentIdentity $identity
     *
     * @return bool
     */
    public function supports(DocumentIdentity $identity);

    /**
     * Index document identified by identity.
     *
     * @param DocumentIdentity $identity
     * @param bool             $viaQueue
     *
     * @return bool
     */
    public function add(DocumentIdentity $identity, $viaQueue = false);

    /**
     * Index document identified by identity.
     *
     * @param DocumentIdentity $identity
     * @param bool             $viaQueue
     *
     * @return bool
     */
    public function update(DocumentIdentity $identity, $viaQueue = false);

    /**
     * Delete document identified by identity.
     *
     * @param DocumentIdentity $identity
     * @param bool             $viaQueue
     *
     * @return bool
     */
    public function delete(DocumentIdentity $identity, $viaQueue = false);

    /**
     * Index all documents.
     *
     * @return bool
     */
    public function indexAll();

    /**
     * Index all documents via queue.
     *
     * @return bool
     */
    public function queueAll();
}
