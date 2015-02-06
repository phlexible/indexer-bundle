<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Phlexible\Bundle\IndexerBundle\Indexer\IndexerInterface;
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
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('indexer:flush')
            ->setDescription('Flush all documents in storage.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', -1);

        $storage = $this->getContainer()->get('phlexible_indexer.storage.default');

        $output->writeln('Flushing storage ' . $storage->getConnectionString());

        $update = $storage->createUpdate()
            ->flush();

        if ($storage->execute($update)) {
            $output->writeln('<info>Flushed.</info>');
        } else {
            $output->writeln('<error>Flush failed.</error>');
        }

        return 0;
    }

}
