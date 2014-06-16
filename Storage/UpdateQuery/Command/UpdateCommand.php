<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Storage\UpdateQuery\Command;

use Phlexible\IndexerBundle\Document\DocumentInterface;

/**
 * Update command
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class UpdateCommand implements CommandInterface
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
