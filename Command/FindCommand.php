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
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Add command.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class FindCommand extends Command
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
            ->setName('indexer:find')
            ->setDescription('Find document in index.')
            ->addArgument('identifier', InputArgument::REQUIRED, 'Document identifier')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $identifier = new DocumentIdentity($input->getArgument('identifier'));

        foreach ($this->indexers as $indexer) {
            /* @var $indexer \Phlexible\Bundle\IndexerBundle\Indexer\IndexerInterface */

            if ($indexer->supports($identifier)) {
                $storage = $indexer->getStorage();

                $output->writeln('Indexer: '.get_class($indexer));
                $output->writeln('  Storage: '.get_class($storage));
                $output->writeln('    DSN: '.$storage->getConnectionString());

                $document = $storage->find($identifier);
                dump($document);
            }
        }

        return 0;
    }
}
