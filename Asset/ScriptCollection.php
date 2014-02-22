<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Asset;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;

/**
 * Script collection
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class ScriptCollection extends AssetCollection
{
    /**
     * @param array $scriptDir
     */
    public function __construct($scriptDir)
    {
        $assets = array(
            new FileAsset($scriptDir . 'Definitions.js'),
            new FileAsset($scriptDir . 'MainPanel.js'),
            new FileAsset($scriptDir . 'menuhandle/SearchHandle.js'),
        );

        parent::__construct($assets);
    }
}
