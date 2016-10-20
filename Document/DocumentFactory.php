<?php

/*
 * This file is part of the phlexible indexer package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\IndexerBundle\Document;

use Phlexible\Bundle\IndexerBundle\Event\DocumentEvent;
use Phlexible\Bundle\IndexerBundle\IndexerEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Document factory
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class DocumentFactory
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var array
     */
    private $prototypes = array();

    /**
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Document factory
     *
     * @param string           $documentClass
     * @param DocumentIdentity $identity
     *
     * @return DocumentInterface
     */
    public function factory($documentClass, DocumentIdentity $identity = null)
    {
        if (!isset($this->prototypes[$documentClass])) {
            // create document
            $document = new $documentClass();

            // fire create event
            $event = new DocumentEvent($document);
            $this->dispatcher->dispatch(IndexerEvents::CREATE_DOCUMENT, $event);

            // cache prototype
            $this->prototypes[$documentClass] = $document;
        }

        $document = clone $this->prototypes[$documentClass];

        if ($identity) {
            $document->setIdentity($identity);
        }

        return $document;
    }
}
