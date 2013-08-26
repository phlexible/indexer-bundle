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
 * Maintenance Command
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
class MWF_Core_Indexer_Command_Maintenance extends MWF_Core_Commands_Command_AbstractCommand
{
    /**
     * @inheritDoc
     */
    public static function getCommand()
    {
        $cmd = new Console_CommandLine_Command(array(
            'name'        => 'indexer:maintenance',
            'description' => 'Maintain available repositories',
        ));

        return $cmd;
    }

    /**
     * @inheritDoc
     */
    public function call(Console_CommandLine_Result $globalArgs, Console_CommandLine_Result $commandArgs)
    {
        ini_set('memory_limit', -1);

        $container = $this->getContainer();
        $outputter = $this->getOutputter();

        $storages = $container->componentCallback->getIndexerStorages();

        $outputter->writeln('Maintaining repositories:');

        foreach ($storages as $id => $storage)
        {
            /* @var $storage MWF_Core_Indexer_Storage */

            $outputter->writeln(' * ' . $id . ':');

            if ($storage->optimize())
            {
                $outputter->writeln('   - optimized');
            }
            else
            {
                $outputter->writeln('   - optimize not supported');
            }
        }

        $outputter->writeln('');
        $outputter->writeln('Maintenance done.');

        return 0;
    }

}
