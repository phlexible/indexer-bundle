<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
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

            $output->writeln('Indexer: ' . $indexer->getName());
            $output->writeln('  Storage: ' . get_class($storage));
            $output->writeln('    DSN: ' . $storage->getConnectionString());

            $result = $indexer->indexAll($viaQueue);
            if (!$result) {
                $output->writeln('Nothing to index.');
            } else {
                if ($viaQueue) {
                    $output->writeln("Queued $result document-adds.");
                } else {
                    $output->writeln("Addded $result documents to index.");
                }
            }
        }

        return 0;
    }

}