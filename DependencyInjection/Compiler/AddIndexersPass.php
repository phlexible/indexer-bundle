<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Add indexers pass
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class AddIndexersPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $indexers = array();
        foreach ($container->findTaggedServiceIds('indexer.indexer') as $id => $definition) {
            $indexers[$id] = new Reference($id);
        }
        $container->getDefinition('indexer.indexers')->replaceArgument(0, $indexers);
    }
}
