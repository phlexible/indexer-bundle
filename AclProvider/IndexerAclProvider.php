<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\AclProvider;

use Phlexible\SecurityBundle\Acl\AclProvider\AclProvider;

/**
 * Indexer acl provider
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class IndexerAclProvider extends AclProvider
{
    /**
     * {@inheritdoc}
     */
    public function provideResources()
    {
        return array(
            'indexer',
        );
    }
}