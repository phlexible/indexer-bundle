<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Event;

use Phlexible\Bundle\IndexerBundle\Model\DocumentInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Document event
 *
 * @author Phillip Look <plook@brainbits.net>
 */
class DocumentEvent extends Event
{
    /**
     * @var DocumentInterface
     */
    private $document = null;

    /**
     * @param DocumentInterface $document
     */
    public function __construct(DocumentInterface $document)
    {
        $this->document = $document;
    }

    /**
     * @return DocumentInterface
     */
    public function getDocument()
    {
        return $this->document;
    }
}
