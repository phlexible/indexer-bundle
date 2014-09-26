<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Storage;

use Phlexible\Bundle\IndexerBundle\Query\Query;
use Phlexible\Bundle\IndexerBundle\Storage\SelectQuery\SelectQuery;
use Phlexible\Bundle\IndexerBundle\Storage\UpdateQuery\UpdateQuery;

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
     * @return SelectQuery
     */
    public function createSelect();

    /**
     * @param SelectQuery $select
     */
    public function select(SelectQuery $select);

    /**
     * @return SuggestQuery
     */
    public function createSuggest();

    /**
     * @param SuggestQuery $suggest
     */
    public function suggest(SuggestQuery $suggest);

    /**
     * @return UpdateQuery
     */
    public function createUpdate();

    /**
     * @param UpdateQuery $update
     *
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
