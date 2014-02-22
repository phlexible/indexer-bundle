<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Command;

use Phlexible\CoreComponent\Command\ContainerAwareCommand;
use Phlexible\IndexerComponent\Indexer\IndexerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Flush command
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class FlushCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('indexer:flush')
            ->setDescription('Flush all repositories.')
            ->addOption('indexer', 'i', InputOption::VALUE_REQUIRED, 'Indexer')
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $onlyIndexer = '';
        if ($input->getOption('indexer'))
        {
            $onlyIndexer = $input->getOption('indexer');
        }

        ini_set('memory_limit', -1);

        $container = $this->getContainer();

        $indexerTools = $container->indexerTools;

        $allStorages = $container->indexerStorages;
        $allIndexers = $container->indexerIndexers;

        foreach ($allIndexers as $indexerId => $indexer)
        {
            /* @var $indexer IndexerInterface */

            if ($onlyIndexer && $indexerId !== $onlyIndexer)
            {
                continue;
            }

            $output->writeln('Indexer: ' . $indexer->getLabel());

            $storages = $indexerTools->filterRepositoriesByAcceptedStorage($indexer->getDocumentType(), $allStorages);
            foreach ($storages as $id => $storage)
            {
                $update = $storage->createUpdate()->addFlush();
                $storage->update($update);

                $output->writeln('Flushing ' . $id);
            }
        }

        $output->writeln('Flushing done.');

        return 0;
    }

}
