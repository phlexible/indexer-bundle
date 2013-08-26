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
 * MWF Indexer Before Remove by Identifier Event
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Phillip Look <plook@brainbits.net>
 * @copyright   2007 brainbits GmbH (http://www.brainbits.net)
 */
class MWF_Core_Indexer_Event_BeforeStorageRemoveByIdentifierEvent
    extends Brainbits_Event_Notification_Abstract
{
    /**
     * @var string
     */
    protected $_notificationName = MWF_Core_Indexer_Event::BEFORE_STORAGE_REMOVE_BY_IDENTIFIER;

    /**
     * @var string
     */
    protected $_identifier = null;

    /**
     * Constructor
     *
     * @param string $identifier
     */
    public function __construct($identifier)
    {
        $this->_identifier = $identifier;
    }

    /**
     * Return identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->_identifier;
    }
}
