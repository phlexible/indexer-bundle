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
 * MWF Indexer Events
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Stephan Wentz <sw@brainbits.net>
 * @copyright   2007 brainbits GmbH (http://www.brainbits.net)
 */
interface MWF_Core_Indexer_Event
{
    /**
     * Create Document Event
     * Fired when a new document is created
     */
    const CREATE_DOCUMENT = 'indexer.create_document';

    /**
     * Before Storage Add Document Event
     * Fired before a document is added to a storage
     */
    const BEFORE_STORAGE_ADD_DOCUMENT = 'indexer.before_storage_add_document';

    /**
     * Storage Add Document Event
     * Fired after a document was added to a storage
     */
    const STORAGE_ADD_DOCUMENT = 'indexer.storage_add_document';

    /**
     * Before Storage Update Document Event
     * Fired before a document is added to a storage
     */
    const BEFORE_STORAGE_UPDATE_DOCUMENT = 'indexer.before_storage_update_document';

    /**
     * Storage Update Document Event
     * Fired after a document was added to a storage
     */
    const STORAGE_UPDATE_DOCUMENT = 'indexer.storage_update_document';

    /**
     * Before Storage Remove By Identifier Event
     * Fired before documents are removed by identifier
     */
    const BEFORE_STORAGE_REMOVE_BY_IDENTIFIER = 'indexer.before_remove_by_identifier';

    /**
     * Storage Remove By Identifier Event
     * Fired after documents are removed by identifier
     */
    const STORAGE_REMOVE_BY_IDENTIFIER = 'indexer.remove_by_identifier';

    /**
     * Before Storage Remove By Query Event
     * Fired before documents are removed by query
     */
    const BEFORE_STORAGE_REMOVE_BY_QUERY = 'indexer.before_remove_by_query';

    /**
     * Storage Remove By Query Event
     * Fired after documents are removed by query
     */
    const STORAGE_REMOVE_BY_QUERY = 'indexer.remove_by_query';

    /**
     * Before Storage Remove All Event
     * Fired before all documents are removed
     */
    const BEFORE_STORAGE_REMOVE_ALL = 'indexer.before_storage_remove_all';

    /**
     * Storage Remove All Event
     * Fired after all documents are removed
     */
    const STORAGE_REMOVE_ALL = 'indexer.storage_remove_all';
}
