<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Storage;

use Phlexible\Bundle\IndexerBundle\Document\DocumentInterface;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\UpdateQuery;

/**
 * Storage interface
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
interface StorageInterface
{
    /**
     * Return connection parameters as string
     *
     * @return string
     */
    public function getConnectionString();

    /**
     * Count all documents
     *
     * @return int
     */
    public function count();

    /**
     * Count all documents of type
     *
     * @param string $type
     *
     * @return int
     */
    public function countType($type);

    /**
     * Add document
     *
     * @param DocumentInterface $document
     */
    public function addDocument(DocumentInterface $document);

    /**
     * Update document
     *
     * @param DocumentInterface $document
     */
    public function updateDocument(DocumentInterface $document);

    /**
     * Delete document
     *
     * @param DocumentInterface $document
     */
    public function deleteDocument(DocumentInterface $document);

    /**
     * Delete document by identifier
     *
     * @param string $identifier
     */
    public function delete($identifier);

    /**
     * Delete documents by type
     *
     * @param string $type
     */
    public function deleteType($type);

    /**
     * Remove all documents
     */
    public function deleteAll();

    /**
     * @return UpdateQuery
     */
    public function createUpdate();

    /**
     * @param UpdateQuery $update
     *
     * @return bool
     */
    public function execute(UpdateQuery $update);

    /**
     * @return boolean
     */
    public function isHealthy();
}
