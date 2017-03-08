<?php

/*
 * This file is part of the phlexible indexer package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\IndexerBundle\Storage;

use Phlexible\Bundle\IndexerBundle\Document\DocumentIdentity;
use Phlexible\Bundle\IndexerBundle\Document\DocumentInterface;
use Phlexible\Bundle\IndexerBundle\Storage\Operation\Operations;

/**
 * Storage interface.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
interface StorageInterface
{
    /**
     * Return connection parameters as string.
     *
     * @return string
     */
    public function getConnectionString();

    /**
     * Count all documents.
     *
     * @return int
     */
    public function count();

    /**
     * Count all documents of type.
     *
     * @param string $type
     *
     * @return int
     */
    public function countType($type);

    /**
     * @param DocumentIdentity $identity
     *
     * @return DocumentInterface
     */
    public function find(DocumentIdentity $identity);

    /**
     * Add document.
     *
     * @param DocumentInterface $document
     *
     * @return int
     */
    public function addDocument(DocumentInterface $document);

    /**
     * Update document.
     *
     * @param DocumentInterface $document
     *
     * @return int
     */
    public function updateDocument(DocumentInterface $document);

    /**
     * Delete document.
     *
     * @param DocumentInterface $document
     *
     * @return int
     */
    public function deleteDocument(DocumentInterface $document);

    /**
     * Delete document by identifier.
     *
     * @param string $identifier
     *
     * @return int
     */
    public function delete($identifier);

    /**
     * Delete documents by type.
     *
     * @param string $type
     *
     * @return int
     */
    public function deleteType($type);

    /**
     * Remove all documents.
     *
     * @return int
     */
    public function deleteAll();

    /**
     * @return Operations
     */
    public function createOperations();

    /**
     * @param Operations $operations
     *
     * @return bool
     */
    public function execute(Operations $operations);

    /**
     * @param Operations $operations
     *
     * @return bool
     */
    public function queue(Operations $operations);

    /**
     * @return bool
     */
    public function isHealthy();

    /**
     * @return array
     */
    public function check();
}
