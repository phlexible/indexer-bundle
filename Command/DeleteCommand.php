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
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\Console\Input\InputOption;

/**
 * Delete command
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class DeleteCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('indexer:delete')
            ->setDescription('Delete document.')
            ->addOption('identifier', null, InputOption::VALUE_REQUIRED, 'Document identifier', '')
            ->addOption('type', null, InputOption::VALUE_REQUIRED, 'Document type', '')
            ->addOption('all', null, InputOption::VALUE_NONE, 'All documents')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $identifier = $input->getOption('identifier');
        $type = $input->getOption('type');
        $all = $input->getOption('all');

        ini_set('memory_limit', -1);

        $indexers = $this->getContainer()->get('phlexible_indexer.indexers');

        $cnt = 0;
        foreach ($indexers as $indexer) {
            /* @var $indexer IndexerInterface */

            $storage = $indexer->getStorage();

            $output->writeln('Indexer: ' . $indexer->getName());
            $output->writeln('  Storage: ' . get_class($storage));
            $output->writeln('    DSN: ' . $storage->getConnectionString());

            if ($all) {
                $cnt += $storage->deleteAll();
            } elseif ($type && $indexer->getType() === $type) {
                $cnt += $storage->deleteType($type);
            } elseif ($identifier && $indexer->supports($identifier)) {
                $cnt += $storage->delete($identifier);
            }
        }

        $output->writeln("Deleted $cnt documents.");

        return 1;
    }
}
