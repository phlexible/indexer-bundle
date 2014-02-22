<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Result;

use Phlexible\IndexerComponent\Document\DocumentInterface;
use Phlexible\IndexerComponent\Result\Sorter\SorterInterface;

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
     * @return $this
     */
    public function addDocument(DocumentInterface $document);

    /**
     * Adds multiple document to local result. If an ID is already in local result, the highest relevance wins
     *
     * @param DocumentInterface[] $documents
     * @return $this
     */
    public function addDocuments(array $documents = array());

    /**
     * Merges a result to local result
     *
     * @param ResultInterface $result
     * @return $this
     */
    public function addResult(ResultInterface $result);

    /**
     * Return result, represented as array of Nodes
     *
     * @return array
     */
    public function getResult();

    /**
     * @param SorterInterface $sorter
     * @return $this
     */
    public function setSorter(SorterInterface $sorter);

    /**
     * @return SorterInterface
     */
    public function getSorter();

    /**
     * Sorts result by nodes relevance
     * @return $this
     */
    public function sort();

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

    /**
     * Remove all current items.
     * @return $this
     */
    public function clear();
}