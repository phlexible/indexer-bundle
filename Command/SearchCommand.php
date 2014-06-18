<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Command;

use Phlexible\IndexerBundle\Query\Filter\TermFilter;
use Phlexible\IndexerBundle\Query\Query;
use Phlexible\IndexerBundle\Query\Query\TermsQuery;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Search command
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class SearchCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('indexer:search')
            ->setDescription('Run search.')
            ->addArgument('queryString', InputArgument::REQUIRED, 'Query string')
            ->addOption('search', null, InputOption::VALUE_REQUIRED, 'Search')
            ->addOption('filter', null, InputOption::VALUE_REQUIRED, 'Filter')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = $this->getContainer()->get('phlexible_indexer.storage.default');
        $select = $client->createSelect()
            ->addDocumentType('media')
            ->addField('title')
            ->setQuery(new TermsQuery('test'))
            ->setFilter(new TermFilter('document_type', 'jpg'))
            ->setHighlight(true)
        ;
        $result = $client->select($select);
        ldd($result);

        $search = '';
        if ($input->getOption('search'))
        {
            $search = $input->getOption('search');
        }

        $filterKey = '';
        $filterValue = '';
        if ($input->getOption('filter'))
        {
            $filter = $input->getOption('filter');

            if (!preg_match('/^(.*)=(.*)$/', $filter, $match))
            {
                echo 'Filter has to be of format key=value' . PHP_EOL;
                die;
            }

            $filterKey   = $match[1];
            $filterValue = $match[2];
        }

        $queryString = $input->getArgument('queryString');

        $container = $this->getContainer();

        $indexerSearch = $container->get('indexer.search');
        $indexerSearches = $container->get('indexer.searches');

        foreach ($indexerSearches as $id => $query)
        {
            if ($search && $search !== $id)
            {
                continue;
            }

            $output->writeln(str_repeat('=', 80));
            $output->writeln(' ' . $id);
            $output->writeln(str_repeat('=', 80));

            if ($filterKey)
            {
                $query->setFilters(array($filterKey => $filterValue));
            }

            $query->parseInput($queryString);
            $result = $indexerSearch->query($query);

            foreach ($result as $document)
            {
                $output->writeln((string)$document);
                $output->writeln(str_repeat('-', 80));
            }
        }

        return 0;
    }

}
