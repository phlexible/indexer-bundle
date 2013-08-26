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
 * Abstract Indexer
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
abstract class MWF_Core_Indexer_Indexer_Abstract implements MWF_Core_Indexer_Indexer_Interface
{
    /**
     * @var MWF_Core_Indexer_Document_Factory
     */
    protected $documentFactory;

    /**
     * @var string
     */
    protected $_label = null;

    /**
     * Use jobs for indexing single documents?
     *
     * @return boolean
     */
    public function useJobs()
    {
        return true;
    }

    /**
     * Return label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->_label;
    }

    /**
     * Return new instance of indexers document class
     *
     * @return MWF_Core_Indexer_Document_Interface
     */
    public function createDocument()
    {
        $documentClass = $this->getDocumentClass();
        $documentType  = $this->getDocumentType();

        $document = $this->documentFactory->factory($documentClass, $documentType);

        return $document;
    }
}