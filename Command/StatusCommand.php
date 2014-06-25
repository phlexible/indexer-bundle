<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Command;

use Phlexible\Bundle\IndexerBundle\Indexer\IndexerCollection;
use Phlexible\Bundle\IndexerBundle\Indexer\IndexerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Phlexible\Bundle\IndexerBundle\Storage\Storage;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Status command
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class StatusCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('indexer:status')
            ->setDescription('Show indexer status.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', -1);

        $storage = $this->getContainer()->get('phlexible_indexer.storage.default');
        #$searches = $this->getContainer()->findTaggedComponents('indexer.search');
        $indexers = $this->getContainer()->get('phlexible_indexer.indexers');

        $this->showIndexers($output, $indexers);
        $output->writeln('');
        $this->showStorage($output, $storage);
        $output->writeln('');
         // $this->_renderSearches($searches) .

        return 0;
    }

    private function showIndexers(OutputInterface $output, IndexerCollection $indexers)
    {
        $output->writeln('Indexers');
        if (!count($indexers)) {
            $output->writeln('  No indexers.');
        } else {
            foreach ($indexers as $indexer) {
                $output->writeln('  <info>' . $indexer->getLabel() . '</info>');
                $output->writeln('    Indexer:  ' . get_class($indexer));
                $output->writeln('    Storage:  ' . $indexer->getStorage()->getLabel());
                $output->writeln('    Document: ' . $indexer->getDocumentClass());
            }
        }
    }

    private function showStorage(OutputInterface $output, Storage $storage)
    {
        $storageAdapter = $storage->getAdapter();

        $output->writeln('Storage');
        $output->writeln('  <info>' . $storage->getLabel() . '</info>');
        $output->writeln('    Storage:        ' . get_class($storage));
        $output->writeln('    Adapter:        ' . get_class($storageAdapter));
        $output->writeln('    Connection:     ' . $storageAdapter->getConnectionString());
        $output->writeln('    Optimizable:    ' . ($storage->isOptimizable() ? 'yes' : 'no'));
        $output->writeln('    Is healthy:     ' . ($storageAdapter->isHealthy() ? 'yes' : 'no'));
    }

    protected function _header($title)
    {
        $ret = array();

        $ret[] = PHP_EOL;
        $ret[] = '=================================================================================';
        $ret[] = '  ' . strtoupper($title);
        $ret[] = '=================================================================================';

        return implode(PHP_EOL, $ret);
    }

    protected function _hr()
    {
        return ' -------------------------------------------------------------------------------';
    }

    protected function _renderSearches($searches)
    {
        $ret = array();
        $ret[] = $this->_header('registered searches');
        $ret[] = ' ';

        if (count($searches))
        {
            foreach ($searches as $id => $search)
            {
                $ret[] = $this->_renderSearch($id, $search);
            }
        }
        else
        {
            $ret[] = ' No registered searches';
        }

        return implode(PHP_EOL, $ret);
    }

    protected function _renderSearch($id, QueryInterface $query)
    {
        $label  = $query->getLabel();
        $boost  = $query->getBoost();
        $parser = $query->getParser();

        $ret = array();
        $ret[] = $this->_hr();
        $ret[] = ' ' . $label . ' (' . $id . ')';
        $ret[] = $this->_hr();
        $ret[] = ' Query class:         ' . get_class($query);
        $ret[] = ' Query for documents: ' . implode(', ', $query->getDocumentTypes());
        $ret[] = ' Query on fields:     ' . implode(', ', $query->getFields());

        if ($parser)
        {
            $ret[] = ' Parser class:        ' . get_class($parser);
        }

        if ($boost)
        {
            $ret[] = ' Boost class:         ' . get_class($boost);

            $boostTable = trim($this->_renderBoostTable($boost, $query->getFields()));
            $boostTable = ' ' . str_replace(PHP_EOL, PHP_EOL . ' ', $boostTable);

            $ret[] = $boostTable;
        }

        return implode(PHP_EOL, $ret);
    }

    protected function _renderBoostTable($boost, $fields)
    {
        $table = new Zend_Text_Table(array('columnWidths' => array(16, 16, 16)));
        $table->appendRow(array('Field Name', 'Field Boost', 'Fuzzy Precision'));

        foreach ($fields as $field)
        {
            $table->appendRow(
                array(
                    $field,
                    (string) $boost->getBoost($field),
                    (string) $boost->getPrecision($field),
                )
            );
        }

        return (string) $table;
    }
}
