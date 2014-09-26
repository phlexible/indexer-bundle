<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Indexer;

use Phlexible\Bundle\IndexerBundle\Document\DocumentFactory;
use Phlexible\Bundle\IndexerBundle\Document\DocumentInterface;

/**
 * Abstract indexer
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
abstract class AbstractIndexer implements IndexerInterface
{
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

        $document = $this->getDocumentFactory()->factory($documentClass);

        return $document;
    }
}