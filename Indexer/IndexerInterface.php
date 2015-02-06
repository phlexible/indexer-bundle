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
     * Return associated storage
     *
     * @return StorageInterface
     */
    public function getStorage();

    /**
     * Returns all identifiers of indexable documents
     *
     * @return array
     */
    public function findIdentifiers();

    /**
     * Returns document for identifier
     *
     * @param string $id
     *
     * @return DocumentInterface
     */
    public function buildDocument($id);

    /**
     * Return document class
     *
     * @return string
     */
    public function getDocumentClass();

    /**
     * Return new instance of indexers document class
     *
     * @return DocumentInterface
     */
    public function createDocument();
}