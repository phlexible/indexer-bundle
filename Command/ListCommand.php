<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Phlexible\IndexerBundle\Storage\Optimizable;
use Phlexible\IndexerBundle\Storage\Storage;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * List command
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class ListCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('indexer:list')
            ->setDescription('Lists available indexers, repositories ans searches')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', -1);

        $storages = $this->getContainer()->get('indexer.storages');
        #$searches = $this->getContainer()->findTaggedComponents('indexer.search');
        $indexers = $this->getContainer()->get('indexer.indexers');

        $output->writeln(
            $this->_renderIndexers($indexers)
         #    . $this->_renderSearches($searches)
             . $this->_renderStorages($storages)
        );

        return 0;
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

    protected function _renderIndexers($indexers)
    {
        $ret = array();
        $ret[] = $this->_header('registered indexers');
        $ret[] = ' ';

        if (!count($indexers)) {
            $ret[] = '  No indexers.';
        } else {
            foreach ($indexers as $id => $indexer)
            {
                $ret[] = $this->_renderIndexer($id, $indexer);
            }
        }

        return implode(PHP_EOL, $ret);
    }

    protected function _renderIndexer($id, $indexer)
    {
        $ret = array();
        $ret[] = $this->_hr();
        $ret[] = ' ' . $indexer->getLabel() . ' (' . $id . ')';
        $ret[] = $this->_hr();
        $ret[] = ' Indexer:    ' . get_class($indexer);
        $ret[] = ' Storage:    ' . get_class($indexer->getStorage());
        $ret[] = ' Adapter:    ' . get_class($indexer->getStorage()->getAdapter());
        $ret[] = ' Provides: ' . $indexer->getDocumentClass();
        return implode(PHP_EOL, $ret);
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

    protected function _renderStorages($storages)
    {
        $ret = array();
        $ret[] = $this->_header('registered storages');
        $ret[] = ' ';

        if (!count($storages)) {
            $ret[] = '  No storages.';
        } else {
            foreach ($storages as $id => $storage)
            {
                $ret[] = $this->_renderStorage($id, $storage);
            }
        }

        return implode(PHP_EOL, $ret);
    }

    protected function _renderStorage($id, Storage $storage)
    {
        $storageAdapter = $storage->getAdapter();

        $ret = array();
        $ret[] = $this->_hr();
        $ret[] = ' ' . $storage->getLabel() . ' (' . $id . ')';
        $ret[] = $this->_hr();
        $ret[] = ' Storage:         ' . get_class($storage);
        $ret[] = ' Adapter:         ' . get_class($storageAdapter);
        $ret[] = ' Handles queries: ' . implode(', ', $storage->getAcceptQuery());
        $ret[] = ' Storage accepts: ' . implode(', ', $storage->getAcceptStorage());
        $ret[] = ' Result of type:  ' . $storage->getResultClass();
        $ret[] = ' Optimizable:     ' . ($storage->isOptimizable() ? 'yes' : 'no');
        $ret[] = ' Connection:      ' . $storageAdapter->getConnectionString();

        return implode(PHP_EOL, $ret);
    }
}
