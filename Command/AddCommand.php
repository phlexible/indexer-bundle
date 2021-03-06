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

use Phlexible\Bundle\IndexerBundle\Document\DocumentIdentity;
use Phlexible\Bundle\IndexerBundle\Indexer\IndexerCollection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Add command.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class AddCommand extends Command
{
    /**
     * @var IndexerCollection
     */
    private $indexers;

    /**
     * @param IndexerCollection $indexers
     */
    public function __construct(IndexerCollection $indexers)
    {
        parent::__construct();

        $this->indexers = $indexers;
    }

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
        $identifier = new DocumentIdentity($input->getArgument('identifier'));
        $method = $input->getOption('update') ? 'update' : 'add';

        ini_set('memory_limit', -1);

        foreach ($this->indexers as $indexer) {
            /* @var $indexer \Phlexible\Bundle\IndexerBundle\Indexer\IndexerInterface */

            if ($indexer->supports($identifier)) {
                $storage = $indexer->getStorage();

                $output->writeln('Indexer: '.get_class($indexer));
                $output->writeln('  Storage: '.get_class($storage));
                $output->writeln('    DSN: '.$storage->getConnectionString());

                if (!$indexer->$method($identifier)) {
                    $output->writeln("<error>$identifier was NOT indexed.</error>");

                    continue;
                }

                $output->writeln("$identifier indexed.");
            }
        }

        return 0;
    }
}
