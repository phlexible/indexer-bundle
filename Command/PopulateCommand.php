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
use Phlexible\IndexerComponent\Job\AddNodeJob;
use Phlexible\IndexerComponent\Storage\Storage;
use Phlexible\QueueComponent\Queue\QueueItem;
use Phlexible\QueueComponent\Queue\QueueService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Populate command
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class PopulateCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('indexer:populate')
            ->setDescription('Builds index with documents from all available indexers on all matching repositories.')
            ->addOption('indexer', 'i', InputOption::VALUE_REQUIRED, 'Indexer')
            ->addOption('document', 'd', InputOption::VALUE_REQUIRED, 'Document')
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

        $onlyDocumentIdentifiers = array();
        if ($input->getOption('document'))
        {
            $onlyDocumentIdentifiers = array($input->getOption('document'));
        }

        ini_set('memory_limit', -1);

        $container = $this->getContainer();

        $queueManager = $container->queueService;
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

            $useJobs = false;//$indexer->useJobs();

            $documentIdList = $indexer->getAllIdentifiers();

            if (count($onlyDocumentIdentifiers))
            {
                $documentIdList = array_intersect($documentIdList, $onlyDocumentIdentifiers);
            }

            if (count($documentIdList))
            {
                $progress = $this->getHelperSet()->get('progress');
                $progress->start($output, count($documentIdList));

                /* @var $storage Storage */
                $storage = $indexer->getStorage();
                $update = $storage->createUpdate();
                foreach ($documentIdList as $documentId)
                {
                    if ($useJobs)
                    {
                        $job = new AddNodeJob();
                        $job->setIdentifier($documentId);
                        $job->setStorageIds($storage->getId());
                        $job->setIndexerId($indexerId);

                        $queueManager->addUniqueJob($job, QueueItem::PRIORITY_LOW);
                    }
                    else
                    {
                        $document = $indexer->getDocumentByIdentifier($documentId);
                        $update->addUpdate($document);
                        $progress->advance();
                    }
                }
                $storage->update($update);

                $progress->finish();
            }
            else
            {
                $output->writeln(' Nothing to do');
            }
        }

        return 0;
    }

}
