<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Storage\Operation;

use Phlexible\Bundle\IndexerBundle\Document\DocumentIdentity;

/**
 * Update identity operation
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class UpdateIdentityOperation implements OperationInterface
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
