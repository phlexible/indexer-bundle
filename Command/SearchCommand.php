<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Command;

use Phlexible\Bundle\IndexerBundle\Query\Facet\TermsFacet;
use Phlexible\Bundle\IndexerBundle\Query\Filter\TermFilter;
use Phlexible\Bundle\IndexerBundle\Query\Query;
use Phlexible\Bundle\IndexerBundle\Query\Suggest;
use Phlexible\Bundle\IndexerBundle\Query\Suggest\TermSuggest;
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
            ->addOption('query', null, InputOption::VALUE_REQUIRED, 'Query string')
            ->addOption('facet', null, InputOption::VALUE_REQUIRED, 'Facet fields, use format "field1,field2')
            ->addOption('filter', null, InputOption::VALUE_REQUIRED, 'Filter term, use format "field:term"')
            ->addOption('suggest', null, InputOption::VALUE_REQUIRED, 'Suggest term, use format "field:term')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $storage = $this->getContainer()->get('phlexible_indexer.storage.default');
        $query = $storage->createQuery();

        $queryString = $input->getOption('query');
        if ($queryString) {
            $query->setQuery(new Query\QueryString($queryString));
        }

        $filter = $input->getOption('filter');
        if ($filter) {
            list($field, $value) = explode(':', $filter);
            $filter = new TermFilter(array($field => $value));
            $query->setFilter($filter);
        }

        $facet = $input->getOption('facet');
        if ($facet) {
            $facetFields = explode(',', $facet);
            $facets = array();
            foreach ($facetFields as $facetField) {
                $facet = new TermsFacet($facetField);
                $facet->setField($facetField);
                $facets[] = $facet;
            }
            $query->setFacets($facets);
        }

        $suggest = $input->getOption('suggest');
        if ($suggest) {
            list($field, $value) = explode(':', $suggest);
            $suggestion = new TermSuggest($field, $field);
            $suggestion->setText($value);
            $suggest = new Suggest($suggestion);
            $query->setSuggest($suggest);
        }

        $result = $storage->query($query);
        ld($result);

        return 0;
    }

}
