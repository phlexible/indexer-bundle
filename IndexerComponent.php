<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent;

use Phlexible\Component\Component;

/**
 * Indexer component
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class IndexerComponent extends Component
{
    const RESOURCE_INDEXER = 'indexer';

    public function __construct()
    {
        $this
            ->setVersion('0.7.0')
            ->setId('indexer')
            ->setPackage('phlexible');
    }
}
