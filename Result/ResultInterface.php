<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Result;

use Phlexible\IndexerBundle\Document\DocumentInterface;
use Phlexible\IndexerBundle\Result\Sorter\SorterInterface;

/**
 * Result interface
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
interface ResultInterface
{
    /**
     * Adds a document to local result. If an ID is already in local result, the highest relevance wins
     *
     * @param DocumentInterface $document
     *
     * @return $this
     */
    public function add(DocumentInterface $document);

    /**
     * Set documents
     *
     * @param DocumentInterface[] $documents
     *
     * @return $this
     */
    public function setDocuments(array $documents);

    /**
     * Adds multiple document to local result. If an ID is already in local result, the highest relevance wins
     *
     * @param DocumentInterface[] $documents
     *
     * @return $this
     */
    public function addDocuments(array $documents = array());

    /**
     * Return all documents
     *
     * @return DocumentInterface[]
     */
    public function getDocuments();

    /**
     * Remove all documents
     *
     * @return $this
     */
    public function removeAll();

    /**
     * Merges a result to local result
     *
     * @param ResultInterface $result
     *
     * @return $this
     */
    public function addResult(ResultInterface $result);

    /**
     * Renders result to HTML by calling nodes toHtml() method
     *
     * @return string
     *
     * @deprecated
     */
    public function toString();

    /**
     * Renders result to array by calling nodes toArray() method
     *
     * @return array
     * @deprecated
     */
    public function toArray();
}