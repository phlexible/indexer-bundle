<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Storage\UpdateQuery\Command;

use Phlexible\IndexerComponent\Document\DocumentInterface;

/**
 * Add command
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class AddCommand implements CommandInterface
{
    private $document;

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
