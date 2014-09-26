<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Command;

use Phlexible\Bundle\IndexerBundle\Indexer\IndexerCollection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Phlexible\Bundle\IndexerBundle\Storage\Storage;
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

        $storage = $this->getContainer()->get('phlexible_indexer.storage.default');
        #$searches = $this->getContainer()->findTaggedComponents('indexer.search');
        $indexers = $this->getContainer()->get('phlexible_indexer.indexers');

        $this->showIndexers($output, $indexers);
        $output->writeln('');
        $this->showStorage($output, $storage);
        $output->writeln('');

        return 0;
    }

    private function showIndexers(OutputInterface $output, IndexerCollection $indexers)
    {
        $output->writeln('Indexers');
        if (!count($indexers)) {
            $output->writeln('  No indexers.');
        } else {
            foreach ($indexers as $indexer) {
                $output->writeln('  <info>' . $indexer->getLabel() . '</info>');
                $output->writeln('    Indexer:  ' . get_class($indexer));
                $output->writeln('    Storage:  ' . $indexer->getStorage()->getLabel());
                $output->writeln('    Document: ' . $indexer->getDocumentClass());
            }
        }
    }

    private function showStorage(OutputInterface $output, Storage $storage)
    {
        $storageAdapter = $storage->getAdapter();

        $output->writeln('Storage');
        $output->writeln('  <info>' . $storage->getLabel() . '</info>');
        $output->writeln('    Storage:        ' . get_class($storage));
        $output->writeln('    Adapter:        ' . get_class($storageAdapter));
        $output->writeln('    Connection:     ' . $storageAdapter->getConnectionString());
        $output->writeln('    Optimizable:    ' . ($storage->isOptimizable() ? 'yes' : 'no'));
        $output->writeln('    Is healthy:     ' . ($storageAdapter->isHealthy() ? 'yes' : 'no'));
    }
}
