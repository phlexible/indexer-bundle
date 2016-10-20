<?php

/*
 * This file is part of the phlexible indexer package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\IndexerBundle\Storage\Operation;

use Phlexible\Bundle\IndexerBundle\Document\DocumentIdentity;

/**
 * Add identity operation
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class AddIdentityOperation implements OperationInterface
{
    /**
     * @var DocumentIdentity
     */
    private $identity;

    /**
     * @param DocumentIdentity $identity
     */
    public function __construct(DocumentIdentity $identity)
    {
        $this->identity = $identity;
    }

    /**
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }
}
