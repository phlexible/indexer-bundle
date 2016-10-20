<?php

/*
 * This file is part of the phlexible indexer package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\IndexerBundle\Event;

use Phlexible\Bundle\IndexerBundle\Document\DocumentInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Document event.
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
