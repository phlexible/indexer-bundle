<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Indexer extension
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class IndexerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../_config'));
        $loader->load('services.yml');

        //$configuration = $this->getConfiguration($container);
        //$config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('indexer.asset.script_path', __DIR__ . '/../_scripts/');
        $container->setParameter('indexer.asset.css_path', __DIR__ . '/../_styles/');
    }
}
