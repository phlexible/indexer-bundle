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
 * MWF Indexer Before Remove by Query Event
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Phillip Look <plook@brainbits.net>
 * @copyright   2007 brainbits GmbH (http://www.brainbits.net)
 */
class MWF_Core_Indexer_Event_BeforeStorageRemoveByQueryEvent
    extends Brainbits_Event_Notification_Abstract
{
    /**
     * @var string
     */
    protected $_notificationName = MWF_Core_Indexer_Event::BEFORE_STORAGE_REMOVE_BY_QUERY;

    /**
     * @var MWF_Core_Indexer_Query_Interface
     */
    protected $_query = null;

    /**
     * Constructor
     *
     * @param MWF_Core_Indexer_Query_Interface $query
     */
    public function __construct(MWF_Core_Indexer_Query_Interface $query)
    {
        $this->_query = $query;
    }

    /**
     * Return query
     *
     * @return MWF_Core_Indexer_Query_Interface
     */
    public function getQuery()
    {
        return $this->_query;
    }
}
