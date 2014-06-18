<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Query;

/**
 * Query
 *
 * @author Marco Fischer <mf@brainbits.net>
 * @author Phillip Look <pl@brainbits.net>
 */
class Query
{
    /**
     * {@inheritdoc}
     */
    public function getFields()
    {
        return array('title', 'tags');
    }
    /**
     * {@inheritdoc}
     */
    public function getDocumentType()
    {
        return array('media', 'elements');
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'Indexer query';
    }
}