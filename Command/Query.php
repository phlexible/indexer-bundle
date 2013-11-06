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
 * Query Command
 *
 * @category    MWF
 * @package     MWF_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
class MWF_Core_Indexer_Command_Query extends  MWF_Core_Commands_Command_AbstractCommand
{
    /**
     * @inheritDoc
     */
    public static function getCommand()
    {
        $cmd = new Console_CommandLine_Command(array(
            'name'        => 'indexer:query',
            'description' => 'Query repositories',
        ));

        /*$cmd->addArgument('search', array(
            'name'   => 'search',
            'action' => 'StoreString',
        ));*/

        $cmd->addArgument('queryString', array(
            'name'   => 'queryString',
            'action' => 'StoreString',
        ));

        $cmd->addOption('search', array(
            'name'       => 'search',
            'short_name' => '-s',
            'long_name'  => '--search',
            'action'     => 'StoreString',
        ));

        $cmd->addOption('filter', array(
            'name'       => 'filter',
            'short_name' => '-f',
            'long_name'  => '--filter',
            'action'     => 'StoreString',
        ));

        return $cmd;
    }

    /**
     * @inheritDoc
     */
    public function call(Console_CommandLine_Result $globalArgs, Console_CommandLine_Result $commandArgs)
    {
        $search = '';
        if (!empty($commandArgs->options['search']))
        {
            $search = $commandArgs->options['search'];
        }

        $filterKey = '';
        $filterValue = '';
        if (!empty($commandArgs->options['filter']))
        {
            $filter = $commandArgs->options['filter'];

            if (!preg_match('/^(.*)=(.*)$/', $filter, $match))
            {
                echo 'Filter has to be of format key=value' . PHP_EOL;
                die;
            }

            $filterKey   = $match[1];
            $filterValue = $match[2];
        }

        $queryString = $commandArgs->args['queryString'];

        $container = $this->getContainer();
        $outputter = $this->getOutputter();

        $indexerSearch = $container->indexerSearch;

        foreach ($container->findTaggedComponents('indexer.search') as $id => $query)
        {
            if ($search && $search !== $id)
            {
                continue;
            }

            $outputter->writeln(str_repeat('=', 80));
            $outputter->writeln(' ' . $id);
            $outputter->writeln(str_repeat('=', 80));

            if ($filterKey)
            {
                $query->setFilters(array($filterKey => $filterValue));
            }

            $query->parseInput($queryString);
            $result = $indexerSearch->query($query);

            foreach ($result as $document)
            {
                $outputter->writeln((string)$document);

                $outputter->writeln(str_repeat('-', 80));
            }
        }

        return 0;
    }

}
