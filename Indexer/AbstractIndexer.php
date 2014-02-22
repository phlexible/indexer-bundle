<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Indexer;

use Phlexible\IndexerComponent\Document\DocumentFactory;
use Phlexible\IndexerComponent\Document\DocumentInterface;

/**
 * Abstract indexer
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
abstract class AbstractIndexer implements IndexerInterface
{
    /**
     * @inheritDoc
     */
    public function useJobs()
    {
        return false;
    }

    /**
     * @return DocumentFactory
     */
    abstract public function getDocumentFactory();


    /**
     * Return new instance of indexers document class
     *
     * @return DocumentInterface
     */
    public function createDocument()
    {
        $documentClass = $this->getDocumentClass();
        $documentType  = $this->getDocumentType();

        $document = $this->getDocumentFactory()->factory($documentClass, $documentType);

        return $document;
    }
}