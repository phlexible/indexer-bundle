<?php

/*
 * This file is part of the phlexible indexer package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\IndexerBundle\Document;

/**
 * Document interface.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 * @author Marco Fischer <mf@brainbits.net>
 * @author Phillip Look <pl@brainbits.net>
 */
interface DocumentInterface
{
    const CONFIG_NOTINDEXED = 'notindexed';
    const CONFIG_MULTIVALUE = 'multivalue';
    const CONFIG_READONLY = 'readonly';
    const CONFIG_HIGHLIGHT = 'highlight';
    const CONFIG_TYPE = 'type';

    const TYPE_TEXT = 'text';
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_FLOAT = 'float';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_DOUBLE = 'double';
    const TYPE_LONG = 'long';
    const TYPE_DATETIME = 'datetime';
    const TYPE_DATE = 'date';
    const TYPE_CURRENCY = 'currency';
    const TYPE_OBJECT = 'object';

    /**
     * Return document name.
     *
     * @return string
     */
    public function getName();

    /**
     * Get values.
     *
     * @return array
     */
    public function all();

    /**
     * Set values.
     *
     * @param array $values
     *
     * @return $this
     */
    public function setValues($values);

    /**
     * Is value set?
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key);

    /**
     * Return value.
     *
     * @param string $key
     *
     * @return array
     */
    public function get($key);

    /**
     * Set value.
     *
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    public function set($key, $value);

    /**
     * Is field available?
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasField($key);

    /**
     * Set fields.
     *
     * @param array $fields
     *
     * @return $this
     */
    public function setFields(array $fields);

    /**
     * Return fields.
     *
     * @return array
     */
    public function getFields();

    /**
     * Set field.
     *
     * @param string $key
     * @param array  $config
     *
     * @return $this
     */
    public function setField($key, array $config = array());

    /**
     * Return field.
     *
     * @param string $key
     *
     * @return array
     */
    public function getField($key);

    /**
     * Remove a field.
     *
     * @param string $key
     *
     * @return $this
     */
    public function removeField($key);

    /**
     * @return string
     */
    public function getIdentity();

    /**
     * @param DocumentIdentity $identity
     *
     * @return $this
     */
    public function setIdentity(DocumentIdentity $identity);

    /**
     * Return document class.
     *
     * @return string
     */
    public function getDocumentClass();

    /**
     * Set relevance.
     *
     * @param mixed $relevance
     *
     * @return $this
     */
    public function setRelevance($relevance);

    /**
     * Get relevance.
     *
     * @return mixed
     */
    public function getRelevance();
}
