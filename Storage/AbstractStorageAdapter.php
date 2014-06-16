<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Storage;

/**
 * Abstract storage adapter
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
abstract class AbstractStorageAdapter implements StorageAdapterInterface
{
    /**
     * @var string
     */
    protected $label = 'Storage adapter';

    /**
     * @var string
     */
    protected $resultClass = 'MWF_Core_Indexer_Document_Interface';

    /**
     * @var array
     */
    protected $acceptQuery = array('MWF_Core_Indexer_Query_Interface');

    /**
     * @var array
     */
    protected $acceptStorage = array();

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * {@inheritdoc}
     */
    public function getResultClass()
    {
        return $this->resultClass;
    }

    /**
     * {@inheritdoc}
     */
    public function getAcceptQuery()
    {
        return $this->acceptQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function getAcceptStorage()
    {
        return $this->acceptStorage;
    }
}