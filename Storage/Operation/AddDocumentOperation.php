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

use Phlexible\Bundle\IndexerBundle\Document\DocumentInterface;

/**
 * Add document operation
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class AddDocumentOperation implements OperationInterface
{
    /**
     * @var DocumentInterface
     */
    private $document;

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
