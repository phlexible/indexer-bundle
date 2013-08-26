<?php
/**
 * Phlexible
 *
 * PHP Version 5
 *
 * @category    MWF
 * @package     MWF_Indexer
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */

/**
 * List Command
 *
 * @category    MWF
 * @package     MWF_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
class MWF_Core_Indexer_Command_List extends MWF_Core_Commands_Command_AbstractCommand
{
    /**
     * @inheritDoc
     */
    public static function getCommand()
    {
        $cmd = new Console_CommandLine_Command(array(
            'name'        => 'indexer:list',
            'description' => 'Lists available indexers, repositories ans searches',
        ));

        return $cmd;
    }

    /**
     * @inheritDoc
     */
    public function call(Console_CommandLine_Result $globalArgs, Console_CommandLine_Result $commandArgs)
    {
        ini_set('memory_limit', -1);

        $callback = $this->getContainer()->componentCallback;

        $storages = $callback->getIndexerStorages();
        $searches = $callback->getIndexerSearches();
        $indexers = $callback->getIndexers();

        $this->getOutputter()->writeln(
            $this->_renderIndexers($indexers)
             . $this->_renderSearches($searches)
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

        foreach ($indexers as $id => $indexer)
        {
            $ret[] = $this->_renderIndexer($id, $indexer);
        }

        return implode(PHP_EOL, $ret);
    }

    protected function _renderIndexer($id, $indexer)
    {
        $ret = array();
        $ret[] = $this->_hr();
        $ret[] = ' ' . $indexer->getLabel() . ' (' . $id . ')';
        $ret[] = $this->_hr();
        $ret[] = ' Class:    ' . get_class($indexer);
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

    protected function _renderSearch($id, MWF_Core_Indexer_Query_Interface $query)
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

        foreach ($storages as $id => $storage)
        {
            $ret[] = $this->_renderStorage($id, $storage);
        }

        return implode(PHP_EOL, $ret);
    }

    protected function _renderStorage($id, MWF_Core_Indexer_Storage $storage)
    {
        $storageAdapter = $storage->getAdapter();
        $isOptimizable  = $storageAdapter instanceof MWF_Core_Indexer_Storage_Adapter_Optimizable;

        $ret = array();
        $ret[] = $this->_hr();
        $ret[] = ' ' . $storage->getLabel() . ' (' . $id . ')';
        $ret[] = $this->_hr();
        $ret[] = ' Storage:         ' . get_class($storage);
        $ret[] = ' Adapter:         ' . get_class($storageAdapter);
        $ret[] = ' Handles queries: ' . implode(', ', $storage->getAcceptQuery());
        $ret[] = ' Storage accepts: ' . implode(', ', $storage->getAcceptStorage());
        $ret[] = ' Result of type:  ' . $storage->getResultClass();
        $ret[] = ' Optimizable:     ' . ($isOptimizable ? 'yes' : 'no');
        $ret[] = ' Connection:      ' . $storageAdapter->getConnectionString();

        return implode(PHP_EOL, $ret);
    }
}
