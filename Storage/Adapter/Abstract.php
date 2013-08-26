<?php
/**
 * Phlexible
 *
 * PHP Version 5
 *
 * @category    MWF
 * @package     MWF_IndexerRepositorySolr
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */

/**
 * Solr Adapter
 *
 * @category    MWF
 * @package     MWF_IndexerRepositorySolr
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
abstract class MWF_Core_Indexer_Storage_Adapter_Abstract
    implements MWF_Core_Indexer_Storage_Adapter_Interface
{
    /**
     * @var string
     */
    protected $_label = 'Apache SOLR storage adapter';

    /**
     * @var string
     */
    protected $_resultClass = 'MWF_Core_Indexer_Document_Interface';

    /**
     * @var array
     */
    protected $_acceptQuery = array('MWF_Core_Indexer_Query_Interface');

    /**
     * @var array
     */
    protected $_acceptStorage = array();

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
     * Return result class
     *
     * @return string
     */
    public function getResultClass()
    {
        return $this->_resultClass;
    }

    /**
     * Return accept query
     *
     * @return array
     */
    public function getAcceptQuery()
    {
        return $this->_acceptQuery;
    }

    /**
     * Return accept storage
     *
     * @return array
     */
    public function getAcceptStorage()
    {
        return $this->_acceptStorage;
    }
}