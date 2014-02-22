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
 * Add indexers pass
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class AddIndexersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $indexers = array();
        foreach (array_keys($container->findTaggedDefinitions('indexer.indexer')) as $id) {
            $indexers[$id] = new Reference($id);
        }
        $container->getDefinition('indexerIndexers')->replaceArgument(0, $indexers);
    }
}
