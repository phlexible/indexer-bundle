<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent;

use Phlexible\IndexerComponent\DependencyInjection\Compiler\AddIndexersPass;
use Phlexible\IndexerComponent\DependencyInjection\Compiler\AddStoragesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Indexer bundle
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class PhlexibleIndexerBundle extends Bundle
{
    const RESOURCE_INDEXER = 'indexer';

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddIndexersPass());
        $container->addCompilerPass(new AddStoragesPass());
    }
}
