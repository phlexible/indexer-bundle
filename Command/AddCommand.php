<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Command;

use Phlexible\Bundle\IndexerBundle\Model\IndexerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Add command
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class AddCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('indexer:add')
            ->setDescription('Add document to index.')
            ->addArgument('identifier', InputArgument::REQUIRED, 'Document identifier')
            ->addOption('update', null, InputOption::VALUE_NONE, 'Update document')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $identifier = $input->getArgument('identifier');
        $method = $input->getOption('update') ? 'update' : 'add';

        ini_set('memory_limit', -1);

        $container = $this->getContainer();

        $indexers = $container->get('phlexible_indexer.indexers');
        foreach ($indexers as $indexer) {
            /* @var $indexer IndexerInterface */

            if ($indexer->supports($identifier)) {
                $storage = $indexer->getStorage();

                $output->writeln('Indexer: ' . $indexer->getName());
                $output->writeln('  Storage: ' . get_class($storage));
                $output->writeln('    DSN: ' . $storage->getConnectionString());

                if (!$indexer->$method($identifier)) {
                    $output->writeln("<error>$identifier was NOT indexed.</error>");

                    return 1;
                }

                $output->writeln("$identifier indexed.");

                return 0;
            }
        }

        $output->writeln("<error>No indexer supports identifier $identifier</error>");

        return 1;
    }

}
