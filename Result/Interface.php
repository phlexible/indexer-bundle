<?php
/**
 * Phlexible
 *
 * PHP Version 5
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */

/**
 * Result Interface
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
interface MWF_Core_Indexer_Result_Interface
{
    /**
     * Adds a document to local result. If an ID is already in local result, the highest relevance wins
     *
     * @param MWF_Core_Indexer_Document_Interface $document
     */
    public function addDocument(MWF_Core_Indexer_Document_Interface $document);

    /**
     * Merges a result to local result
     *
     * @param MWF_Core_Indexer_Result_Interface $result
     */
    public function addResult(MWF_Core_Indexer_Result_Interface $result);

    /**
     * Return result, represented as array of Nodes
     *
     * @return array
     */
    public function getResult();

    /**
     * Sorts result by nodes relevance
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
     *
     * @deprecated
     */
    public function toArray();

    /**
     * Remove all current items.
     */
    public function clear();

    /**
     * @param MWF_Core_Indexer_Result_Sorter_Interface $sorter
     */
    public function setSorter(MWF_Core_Indexer_Result_Sorter_Interface $sorter);
}