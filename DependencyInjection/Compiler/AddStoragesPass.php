<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Add storages pass
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class AddStoragesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $storages = array();
        foreach ($container->findTaggedServiceIds('phlexible_indexer.storage') as $id => $definition) {
            $storages[$id] = new Reference($id);
        }
        $container->getDefinition('phlexible_indexer.storages')->replaceArgument(0, $storages);
    }
}
