<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Storage;

use Phlexible\Bundle\IndexerBundle\Document\DocumentInterface;
use Phlexible\Bundle\IndexerBundle\Query\Query;

/**
 * Storage adapter interface
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
interface StorageAdapterInterface
{
    /**
     * Return connection parameters as string
     *
     * @return string
     */
    public function getConnectionString();

    /**
     * Return document by identifier
     *
     * @param string $identifier
     *
     * @return DocumentInterface
     */
    public function getByIdentifier($identifier);

    /**
     * Return documents by query
     *
     * @param Query $query
     *
     * @return DocumentInterface[]
     */
    public function getByQuery(Query $query);

    /**
     * Return all documents
     *
     * @return array of MWF_Core_Indexer_Document_Interface
     */
    public function getAll();

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
     * Remove document
     *
     * @param DocumentInterface $document
     */
    public function removeDocument(DocumentInterface $document);

    /**
     * Remove document by identifier
     *
     * @param string $identifier
     */
    public function removeByIdentifier($identifier = null);

    /**
     * Remove documents by query
     *
     * @param Query $query
     */
    public function removeByQuery(Query $query);

    /**
     * Remove documents by class
     *
     * @param string $class
     */
    public function removeByClass($class);

    /**
     * Remove all documents
     */
    public function removeAll();

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @return string
     */
    public function getId();

    /**
     * @return bool
     */
    public function isHealthy();

    /**
     * Optimize index
     */
    public function optimize();

    /**
     * Commit index
     */
    public function commit();
}