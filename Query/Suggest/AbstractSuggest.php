<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Query\Suggest;

use Phlexible\Bundle\IndexerBundle\Query\QueryParam;

/**
 * Abstract suggest
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
abstract class AbstractSuggest extends QueryParam implements SuggestInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @param string $name
     * @param string $field
     */
    public function __construct($name, $field)
    {
        $this->name = $name;
        $this->setField($field);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setText($text)
    {
        return $this->setParam('text', $text);
    }

    /**
     * {@inheritdoc}
     */
    public function setField($field)
    {
        return $this->setParam("field", $field);
    }

    /**
     * {@inheritdoc}
     */
    public function setSize($size)
    {
        return $this->setParam("size", $size);
    }

    /**
     * {@inheritdoc}
     */
    public function setShardSize($size)
    {
        return $this->setParam("shard_size", $size);
    }
}