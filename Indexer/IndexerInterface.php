<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Indexer;

use Phlexible\IndexerComponent\Document\DocumentInterface;
use Phlexible\MediaCacheComponent\Storage\StorageInterface;

/**
 * Indexer interface
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
interface IndexerInterface
{
    /**
     * Return label
     *
     * @return string
     */
    public function getLabel();

    /**
     * Use jobs for indexing single documents?
     *
     * @return boolean
     */
    public function useJobs();

    /**
     * @return StorageInterface
     */
    public function getStorage();

    /**
     * Returns all identifiers of indexable documents
     *
     * @return array
     */
    public function getAllIdentifiers();

    /**
     * Returns document for identifier
     *
     * @param string $id
     * @return DocumentInterface
     */
    public function getDocumentByIdentifier($id);

    /**
     * Return document class
     *
     * @return string
     */
    public function getDocumentClass();

    /**
     * Return document type
     *
     * @return string
     */
    public function getDocumentType();

    /**
     * Return new instance of indexers document class
     *
     * @return DocumentInterface
     */
    public function createDocument();
}