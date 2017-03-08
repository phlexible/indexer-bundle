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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Delete command.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class DeleteCommand extends Command
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

        $cnt = 0;
        foreach ($this->indexers as $indexer) {
            /* @var $indexer \Phlexible\Bundle\IndexerBundle\Indexer\IndexerInterface */

            $storage = $indexer->getStorage();

            $output->writeln('Indexer: '.get_class($indexer));
            $output->writeln('  Storage: '.get_class($storage));
            $output->writeln('    DSN: '.$storage->getConnectionString());

            if ($all) {
                $cnt += $storage->deleteAll();
            } elseif ($type && $indexer->getType() === $type) {
                $cnt += $storage->deleteType($type);
            } elseif ($identifier) {
                $identity = new DocumentIdentity($identifier);
                if ($indexer->supports($identity)) {
                    $cnt += $storage->delete($identity);
                }
            }
        }

        $output->writeln("Deleted $cnt documents.");

        return 0;
    }
}
