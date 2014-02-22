<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Command;

use Phlexible\CoreComponent\Command\ContainerAwareCommand;
use Phlexible\IndexerComponent\Storage\Optimizable;
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
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('indexer:optimize')
            ->setDescription('Run maintenance on all repositories')
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', -1);

        $storages = $this->getContainer()->indexerStorages;

        $output->writeln('Optimizing repositories:');

        foreach ($storages as $id => $storage)
        {
            $output->writeln(' * ' . $id . ':');

            if ($storage->isOptimizable()) {
                if ($storage->optimize())
                {
                    $output->writeln('   - optimized.');
                }
                else
                {
                    $output->writeln('   - optimize failed.');
                }
            } else {
                $output->writeln('   - optimize not supported.');
            }
        }

        $output->writeln('');
        $output->writeln('Optimize finished.');

        return 0;
    }

}
