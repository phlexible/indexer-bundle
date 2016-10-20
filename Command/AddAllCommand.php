<?php

/*
 * This file is part of the phlexible indexer package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\IndexerBundle\Command;

use Phlexible\Bundle\IndexerBundle\Indexer\IndexerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Add all command
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class AddAllCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('indexer:add-all')
            ->setDescription('Index documents from all indexers.')
            ->addOption('queue', null, InputOption::VALUE_NONE, 'Queue updates instead of immediate run.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $viaQueue = $input->getOption('queue');

        ini_set('memory_limit', -1);

        $indexers = $this->getContainer()->get('phlexible_indexer.indexers');
        foreach ($indexers as $indexer) {
            /* @var $indexer IndexerInterface */
            $storage = $indexer->getStorage();

            $output->writeln('Indexer: ' . get_class($indexer));
            $output->writeln('  Storage: ' . get_class($storage));
            $output->writeln('    DSN: ' . $storage->getConnectionString());

            $result = $indexer->indexAll($viaQueue);
            if (!$result) {
                $output->writeln('Nothing to index.');
            } else {
                if ($viaQueue) {
                    $output->writeln("Queued $result document-adds.");
                } else {
                    $output->writeln("Added $result documents to index.");
                }
            }
        }

        return 0;
    }

}
