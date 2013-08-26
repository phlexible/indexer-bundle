<?php
/**
 * Phlexible
 *
 * PHP Version 5
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */

/**
 * Flush Command
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
class MWF_Core_Indexer_Command_Flush implements MWF_Core_Commands_Command_Interface
{
    /**
     * @inheritDoc
     */
    public static function getCommand()
    {
        $cmd = new Console_CommandLine_Command(array(
            'name'        => 'indexer:flush',
            'description' => 'Flush all repositories',
        ));

        $cmd->addOption(
            'indexer',
            array(
                'name'       => 'indexer',
                'short_name' => '-i',
                'long_name'  => '--indexer',
                'action'     => 'StoreString',
            )
        );

        return $cmd;
    }

    /**
     * @inheritDoc
     */
    public function call(Console_CommandLine_Result $globalArgs, Console_CommandLine_Result $commandArgs)
    {
        $onlyIndexer = '';
        if (!empty($commandArgs->options['indexer']))
        {
            $onlyIndexer = $commandArgs->options['indexer'];
        }

        $output = '';

        ini_set('memory_limit', -1);

        $container = $this->getContainer();
        $outputter = $this->getOutputter();

        $componentCallback = $container->componentCallback;
        $indexerTools      = $container->indexerTools;

        $allStorages = $componentCallback->getIndexerStorages();
        $allIndexers = $componentCallback->getIndexers();

        foreach ($allIndexers as $indexerId => $indexer)
        {
            /* @var $indexer MWF_Core_Indexer_Indexer_Interface */

            if ($onlyIndexer && $indexerId !== $onlyIndexer)
            {
                continue;
            }

            $outputter->writeln('Indexer: ' . $indexer->getLabel());

            $storages = $indexerTools->filterRepositoriesByAcceptedStorage($indexer->getDocumentType(), $allStorages);
            foreach ($storages as $id => $storage)
            {
                $storage->removeAll();
                $outputter->writeln('Flushing ' . $id);
            }
        }

        $output->writeln('Flushing done.');

        return 0;
    }

}
