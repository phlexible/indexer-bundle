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
 * Add document operation
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class AddDocumentOperation implements OperationInterface
{
    /**
     * @var \Phlexible\Bundle\IndexerBundle\Document\DocumentInterface
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
