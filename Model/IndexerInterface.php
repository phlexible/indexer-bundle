<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Model;

/**
 * Indexer interface
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
interface IndexerInterface
{
    /**
     * Return name
     *
     * @return string
     */
    public function getName();

    /**
     * Return type
     *
     * @return string
     */
    public function getType();

    /**
     * Return associated storage
     *
     * @return StorageInterface
     */
    public function getStorage();

    /**
     * @param string|DocumentInterface $identifier
     *
     * @return bool
     */
    public function supports($identifier);

    /**
     * Index document identified by identifier
     *
     * @param string $identifier
     * @param bool   $viaQueue
     *
     * @return bool
     */
    public function add($identifier, $viaQueue = false);

    /**
     * Index document identified by identifier
     *
     * @param string $identifier
     * @param bool   $viaQueue
     *
     * @return bool
     */
    public function update($identifier, $viaQueue = false);

    /**
     * Delete document identified by identifier
     *
     * @param string $identifier
     * @param bool   $viaQueue
     *
     * @return bool
     */
    public function delete($identifier, $viaQueue = false);

    /**
     * Index all documents
     *
     * @param bool $viaQueue
     *
     * @return bool
     */
    public function indexAll($viaQueue = false);
}
