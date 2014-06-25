<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Phlexible\Bundle\IndexerBundle\Storage\Optimizable;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Optimize command
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class OptimizeCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('indexer:optimize')
            ->setDescription('Run maintenance on all repositories')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', -1);

        $storage = $this->getContainer()->get('phlexible_indexer.storage.default');

        $output->writeln('Committing changes in storage ' . $storage->getLabel());

        if ($storage->isOptimizable()) {
            $update = $storage->createUpdate()->addOptimize();

            if ($storage->update($update)) {
                $output->writeln('<info>Optimized</info>');
            } else {
                $output->writeln('<erro>Optimize failed</erro>');
            }
        } else {
            $output->writeln('Optimize not supported.');
        }

        return 0;
    }

}
