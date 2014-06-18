<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Document;

use Phlexible\IndexerBundle\Event\DocumentEvent;
use Phlexible\IndexerBundle\IndexerEvents;
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
    private $dispatcher = null;

    /**
     * @var array
     */
    private $prototypes = array();

    /**
     * @var \Zend_Filter_Interface
     */
    private $fieldNameFilter;

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param \Zend_Filter_Interface   $fieldNameFilter
     */
    public function __construct(EventDispatcherInterface $dispatcher,
                                \Zend_Filter_Interface $fieldNameFilter = null)
    {
        $this->dispatcher      = $dispatcher;
        $this->fieldNameFilter = $fieldNameFilter;
    }

    /**
     * Document factory
     *
     * @param string $documentClass
     * @param string $documentType
     *
     * @return DocumentInterface
     */
    public function factory($documentClass, $documentType)
    {
        $prototypeKey = $documentClass . '___' . $documentType;

        if (!isset($this->prototypes[$prototypeKey])) {
            // create document
            $document = new $documentClass($documentType);

            // apply field name filter
            if ($this->fieldNameFilter && method_exists($document, 'setFieldNameFilter')) {
                $document->setFieldNameFilter($this->fieldNameFilter);
            }

            // fire create event
            $event = new DocumentEvent($document);
            $this->dispatcher->dispatch(IndexerEvents::CREATE_DOCUMENT, $event);

            // cache prototype
            $this->prototypes[$prototypeKey] = $document;
        }

        return clone $this->prototypes[$prototypeKey];
    }
}