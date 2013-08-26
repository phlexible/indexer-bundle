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
 * Build Command
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
class MWF_Core_Indexer_Command_Build extends  MWF_Core_Commands_Command_AbstractCommand
{
    /**
     * @inheritDoc
     */
    public static function getCommand()
    {
        $cmd = new Console_CommandLine_Command(array(
            'name'        => 'indexer:build',
            'description' => 'Builds index with documents from all available indexers on all matching repositories',
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

        $cmd->addOption(
            'document',
            array(
                'name'       => 'document',
                'short_name' => '-d',
                'long_name'  => '--document',
                'action'     => 'StoreString',
            )
        );

        return $cmd;
    }

    /**
     * @inheritDoc
     */
    public function call(Console_CommandLine_Result $globalArgs,
                         Console_CommandLine_Result $commandArgs)
    {
        $onlyIndexer = '';
        if (!empty($commandArgs->options['indexer']))
        {
            $onlyIndexer = $commandArgs->options['indexer'];
        }

        $onlyDocumentIdentifiers = array();
        if (!empty($commandArgs->options['document']))
        {
            $onlyDocumentIdentifiers = array($commandArgs->options['document']);
        }

        ini_set('memory_limit', -1);

        $adapter = new Zend_ProgressBar_Adapter_Console(array(
            'elements' => array(
                Zend_ProgressBar_Adapter_Console::ELEMENT_PERCENT,
                Zend_ProgressBar_Adapter_Console::ELEMENT_BAR,
                Zend_ProgressBar_Adapter_Console::ELEMENT_ETA,
                Zend_ProgressBar_Adapter_Console::ELEMENT_TEXT
            ),
            'textWidth' => 60
        ));

        $outputter = $this->getOutputter();
        $container = $this->getContainer();

        $componentCallback = $container->componentCallback;
        $queueManager     = $container->queueManager;
        $indexerTools     = $container->indexerTools;

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

            $useJobs = $indexer->useJobs();

            $storages = $indexerTools->filterRepositoriesByAcceptedStorage($indexer->getDocumentType(), $allStorages);

            if (count($storages) == 0)
            {
                $outputter->writeln('NO STORAGE CAN STORE DOCUMENTS (' . $indexer->getDocumentType() . ') FROM ' . $indexer->getLabel() . ' (' . get_class($indexer) . '), skipping.');
                continue;
            }

            $documentIdList = $indexer->getAllIdentifiers();

            if (count($onlyDocumentIdentifiers))
            {
                $documentIdList = array_intersect($documentIdList, $onlyDocumentIdentifiers);
            }

            if (count($documentIdList))
            {
                $bar = new Zend_ProgressBar($adapter, 0, count($documentIdList));

                foreach ($documentIdList as $documentId)
                {
                    $job = new MWF_Core_Indexer_Job_AddNode();
                    $job->setIdentifier($documentId);
                    $job->setStorageIds(array_keys($storages));
                    $job->setIndexerId($indexerId);

                    if ($useJobs)
                    {
                        $queueManager->addUniqueJob($job, MWF_Core_Queue_Manager::PRIORITY_LOW);
                    }
                    else
                    {
                        $job->work();
                    }

                    $bar->next(1, $documentId);
                }

                $bar->finish();
            }
            else
            {
                $outputter->writeln(' Nothing to do');
            }
        }

        return 0;
    }

}
