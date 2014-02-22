<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent;
use Phlexible\Component\AbstractComponent;

/**
 * Indexer component
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class IndexerComponent extends AbstractComponent
{
    const RESOURCE_INDEXER = 'indexer';

    public function __construct()
    {
        $this
            ->setVersion('0.7.0')
            ->setId('indexer')
            ->setPackage('phlexible');
    }

    /**
     * ACL Callback
     *
     * Returns all ACL resources this component provides
     *
     * @return array
     */
    public function getAcl()
    {
        return array(
            array(
                'roles' => array(
                ),
                'resources' => array(
                    self::RESOURCE_INDEXER,
                ),
                'allow' => array(
                )
            )
        );
    }
}
