<?php

/*
 * This file is part of the phlexible indexer package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\IndexerBundle\DependencyInjection\Compiler;

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
        foreach ($container->findTaggedServiceIds('phlexible_indexer.indexer') as $id => $definition) {
            $indexers[$id] = new Reference($id);
        }
        $container->getDefinition('phlexible_indexer.indexers')->replaceArgument(0, $indexers);
    }
}
