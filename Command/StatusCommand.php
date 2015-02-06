<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Command;

use Phlexible\Bundle\IndexerBundle\Indexer\IndexerCollection;
use Phlexible\Bundle\IndexerBundle\Storage\Commitable;
use Phlexible\Bundle\IndexerBundle\Storage\Flushable;
use Phlexible\Bundle\IndexerBundle\Storage\Optimizable;
use Phlexible\Bundle\IndexerBundle\Storage\StorageInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Status command
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class StatusCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('indexer:status')
            ->setDescription('Show indexer status.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', -1);

        $indexers = $this->getContainer()->get('phlexible_indexer.indexers');

        $this->showIndexers($output, $indexers);

        return 0;
    }

    private function showIndexers(OutputInterface $output, IndexerCollection $indexers)
    {
        $output->writeln('Indexers:');
        if (!count($indexers)) {
            $output->writeln('  No indexers.');
        } else {
            foreach ($indexers as $indexer) {
                $output->writeln('  <info>' . $indexer->getLabel() . '</info>');
                $output->writeln('    Indexer: ' . get_class($indexer));

                $this->showStorage($output, $indexer->getStorage());
            }
        }
    }

    private function showStorage(OutputInterface $output, StorageInterface $storage)
    {
        $features = array();
        if ($storage instanceof Flushable) {
            $features[] = 'Flushable';
        }
        if ($storage instanceof Optimizable) {
            $features[] = 'Optimizable';
        }
        if ($storage instanceof Commitable) {
            $features[] = 'Commitable';
        }
        $output->writeln('    Storage: <info>' . $storage->getConnectionString() . '</info>');
        $output->writeln('        Class:      ' . get_class($storage));
        $output->writeln('        Connection: ' . $storage->getConnectionString());
        $output->writeln('        Features:   ' . implode(', ', $features));
        $output->writeln('        Is healthy: ' . ($storage->isHealthy() ? '<info>yes</info>' : '<error>no</error>'));
    }
}
