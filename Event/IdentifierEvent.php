<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Identifier event
 *
 * @author Phillip Look <plook@brainbits.net>
 */
abstract class IdentifierEvent extends Event
{
    /**
     * @var string
     */
    private $identifier = null;

    /**
     * @param string $identifier
     */
    public function __construct($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }
}
