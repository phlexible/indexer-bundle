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
 * MWF Indexer Create Document Event
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Stephan Wentz <sw@brainbits.net>
 * @author      Phillip Look <plook@brainbits.net>
 * @copyright   2007 brainbits GmbH (http://www.brainbits.net)
 */
class MWF_Core_Indexer_Event_CreateDocument
    extends MWF_Core_Indexer_Event_AbstractModifyDocumentEvent
{
    /**
     * @var string
     */
    protected $_notificationName = MWF_Core_Indexer_Event::CREATE_DOCUMENT;
}
