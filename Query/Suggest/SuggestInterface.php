<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Query\Suggest;

/**
 * Suggest interface
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
interface SuggestInterface
{
    /**
     * Retrieve the name of this suggestion
     * @return string
     */
    public function getName();

    /**
     * Suggest text must be set either globally or per suggestion
     *
     * @param string $text
     *
     * @return $this
     */
    public function setText($text);

    /**
     * @param string $field
     *
     * @return $this
     */
    public function setField($field);

    /**
     * @param int $size
     *
     * @return $this
     */
    public function setSize($size);

    /**
     * Maximum number of suggestions to be retrieved from each shard
     *
     * @param int $size
     *
     * @return $this
     */
    public function setShardSize($size);
}