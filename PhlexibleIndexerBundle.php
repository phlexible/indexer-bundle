<?php

/*
 * This file is part of the phlexible indexer package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\IndexerBundle;

use Phlexible\Bundle\IndexerBundle\DependencyInjection\Compiler\AddIndexersPass;
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
    }
}
