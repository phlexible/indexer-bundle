<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Document;

/**
 * Document identity
 * Helper for bi-directional identifier (e.g. element_20_de) resolution
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class DocumentIdentity
{
    /**
     * @var string
     */
    private $identifier;

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

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->identifier;
    }
}
