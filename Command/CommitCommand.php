<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Commit command
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class CommitCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('indexer:commit')
            ->setDescription('Commit changes in storage.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', -1);

        $storage = $this->getContainer()->get('phlexible_indexer.storage.default');

        $output->writeln('Committing storage ' . $storage->getConnectionString());

        $update = $storage->createUpdate()
            ->commit();

        if ($storage->execute($update)) {
            $output->writeln('<info>Committed.</info>');
        } else {
            $output->writeln('<erro>Commit failed.</erro>');
        }

        return 0;
    }

}
