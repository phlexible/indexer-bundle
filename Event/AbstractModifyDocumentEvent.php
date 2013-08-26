<?php
/**
 * MWF - MAKEweb Framework
 *
 * PHP Version 5
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @copyright   2007 brainbits GmbH (http://www.brainbits.net)
 * @version     SVN: $Id: Generator.php 2312 2007-01-25 18:46:27Z swentz $
 */

/**
 * MWF Indexer Abstract Modify Document Event
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Phillip Look <plook@brainbits.net>
 * @copyright   2007 brainbits GmbH (http://www.brainbits.net)
 */
abstract class MWF_Core_Indexer_Event_AbstractModifyDocumentEvent
    extends Brainbits_Event_Notification_Abstract
{
    /**
     * @var MWF_Core_Indexer_Document_Interface
     */
    protected $_document = null;

    /**
     * Constructor
     *
     * @param MWF_Core_Indexer_Document_Interface $document
     */
    public function __construct(MWF_Core_Indexer_Document_Interface $document)
    {
        $this->_document = $document;
    }

    /**
     * Return document
     *
     * @return MWF_Core_Indexer_Document_Interface
     */
    public function getDocument()
    {
        return $this->_document;
    }
}
