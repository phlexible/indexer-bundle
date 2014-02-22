<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Storage;

use Phlexible\IndexerComponent\Document\DocumentInterface;
use Phlexible\IndexerComponent\Query\QueryInterface;
use Phlexible\IndexerComponent\Storage\UpdateQuery\UpdateQuery;

/**
 * Storage interface
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
interface StorageInterface
{
    /**
     * @return StorageAdapterInterface
     */
    public function getAdapter();

    /**
     * @return integer
     */
    public function getPreference();

    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @return string
     */
    public function getResultClass();

    /**
     * @return array
     */
    public function getAcceptQuery();

    /**
     * @return array
     */
    public function getAcceptStorage();

    /**
     * @param null $identifier
     * @return DocumentInterface
     */
    public function getByIdentifier($identifier = null);

    /**
     * @param QueryInterface $query
     * @return array
     */
    public function getByQuery(QueryInterface $query);

    public function getAll();

    /**
     * @return SelectQuery
     */
    public function createSelect();

    /**
     * @param SelectQuery $select
     */
    public function select(SelectQuery $select);

    /**
     * @return UpdateQuery
     */
    public function createUpdate();

    /**
     * @param UpdateQuery $update
     * @return $this
     */
    public function update(UpdateQuery $update);

    /**
     * @return boolean
     */
    public function isOptimizable();

    /**
     * @return $this
     */
    public function optimize();

    /**
     * @return boolean
     */
    public function isHealthy();
}
