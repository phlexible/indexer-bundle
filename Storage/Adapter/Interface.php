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
 * Storage Adapter Interface
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
interface MWF_Core_Indexer_Storage_Adapter_Interface
{
    const PREFERENCE_DO_NOT_USE  = 0;
    const PREFERENCE_LOW         = 10;
    const PREFERENCE_HIGH        = 50;
    const PREFERENCE_FIRST_COICE = 100;

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
     * @return MWF_Core_Indexer_Document_Interface
     */
    public function getByIdentifier($identifier);

    /**
     * Return documents by query
     *
     * @param MWF_Core_Indexer_Query_Interface $query
     * @return array of MWF_Core_Indexer_Document_Interface
     */
    public function getByQuery(MWF_Core_Indexer_Query_Interface $query);

    /**
     * Return all documents
     *
     * @return array of MWF_Core_Indexer_Document_Interface
     */
    public function getAll();

    /**
     * Add document
     *
     * @param MWF_Core_Indexer_Document_Interface $document
     */
    public function addDocument(MWF_Core_Indexer_Document_Interface $document);

    /**
     * Update document
     *
     * @param MWF_Core_Indexer_Document_Interface $document
     */
    public function updateDocument(MWF_Core_Indexer_Document_Interface $document);

    /**
     * Remove document by identifier
     *
     * @param string $identifier
     */
    public function removeByIdentifier($identifier = null);

    /**
     * Remove documents by query
     *
     * @param MWF_Core_Indexer_Query_Interface $query
     */
    public function removeByQuery(MWF_Core_Indexer_Query_Interface $query);

    /**
     * Remove all documents
     */
    public function removeAll();

    /**
     * Return preference
     *
     * @return integer
     */
    public function getPreference();

    public function getLabel();

    public function getResultClass();

    public function getAcceptQuery();

    public function getAcceptStorage();

    public function getId();

    /**
     * Is this adapter healthy?
     *
     * @return boolean
     */
    public function isHealthy();
}