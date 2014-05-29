<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent;

use Phlexible\Component\Component;
use Phlexible\IndexerComponent\DependencyInjection\Compiler\AddIndexersPass;
use Phlexible\IndexerComponent\DependencyInjection\Compiler\AddStoragesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Indexer bundle
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class IndexerBundle extends Component
{
    const RESOURCE_INDEXER = 'indexer';

    public function __construct()
    {
        $this
            ->setVersion('0.7.0')
            ->setId('indexer')
            ->setPackage('phlexible');
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddIndexersPass());
        $container->addCompilerPass(new AddStoragesPass());
    }
}
