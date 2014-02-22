<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Container\Compiler;

use Phlexible\Container\Compiler\CompilerPassInterface;
use Phlexible\Container\ContainerBuilder;
use Phlexible\Container\Definition\Reference;

/**
 * Add storages pass
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class AddStoragesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $storages = array();
        foreach (array_keys($container->findTaggedDefinitions('indexer.storage')) as $id) {
            $storages[$id] = new Reference($id);
        }
        $container->getDefinition('indexerStorages')->replaceArgument(0, $storages);
    }
}
