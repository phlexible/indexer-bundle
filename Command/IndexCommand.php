<?php

/*
 * This file is part of the phlexible indexer package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\IndexerBundle\Command;

use Phlexible\Bundle\IndexerBundle\Storage\StorageInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Index command.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class IndexCommand extends Command
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        parent::__construct();

        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('indexer:index')
            ->setDescription('Run maintenance on index.')
            ->addOption('commit', null, InputOption::VALUE_NONE, 'Commit index')
            ->addOption('flush', null, InputOption::VALUE_NONE, 'Flush index')
            ->addOption('optimize', null, InputOption::VALUE_NONE, 'Optimize index')
            ->addOption('rollback', null, InputOption::VALUE_NONE, 'Rollback index')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', -1);

        $output->writeln('Committing storage '.$this->storage->getConnectionString());

        $operations = $this->storage->createOperations();

        if ($input->getOption('commit')) {
            $operations->commit();
        }
        if ($input->getOption('flush')) {
            $operations->flush();
        }
        if ($input->getOption('optimize')) {
            $operations->optimize();
        }
        if ($input->getOption('rollback')) {
            $operations->rollback();
        }

        if ($this->storage->execute($operations)) {
            $output->writeln('<info>Maintenance done.</info>');
        } else {
            $output->writeln('<erro>Maintenance failed.</erro>');
        }

        return 0;
    }
}
