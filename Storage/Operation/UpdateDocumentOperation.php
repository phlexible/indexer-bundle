<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Storage\Operation;

use Phlexible\Bundle\IndexerBundle\Document\DocumentInterface;

/**
 * Update document operation
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class UpdateDocumentOperation implements OperationInterface
{
    /**
     * @param \Phlexible\Bundle\IndexerBundle\Document\DocumentInterface $document
     */
    private $document;

    /**
     * @param \Phlexible\Bundle\IndexerBundle\Document\DocumentInterface $document
     */
    public function __construct(DocumentInterface $document)
    {
        $this->document = $document;
    }

    /**
     * @return \Phlexible\Bundle\IndexerBundle\Document\DocumentInterface
     */
    public function getDocument()
    {
        return $this->document;
    }
}
