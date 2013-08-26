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
 * Indexer Interface
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
interface MWF_Core_Indexer_Indexer_Interface
{
    /**
     * Use jobs for indexing single documents?
     *
     * @return boolean
     */
    public function useJobs();

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
     * @return MWF_Core_Indexer_Document_Interface
     */
    public function getDocumentByIdentifier($id);

    /**
     * Return label
     *
     * @return string
     */
    public function getLabel();

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
     * @return MWF_Core_Indexer_Document_Interface
     */
    public function createDocument();
}