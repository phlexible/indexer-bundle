<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
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
     *
     * @return DocumentInterface
     */
    public function factory($documentClass)
    {
        $prototypeKey = $documentClass;

        if (!isset($this->prototypes[$documentClass])) {
            // create document
            $document = new $documentClass();

            // apply field name filter
            if ($this->fieldNameFilter && method_exists($document, 'setFieldNameFilter')) {
                $document->setFieldNameFilter($this->fieldNameFilter);
            }

            // fire create event
            $event = new DocumentEvent($document);
            $this->dispatcher->dispatch(IndexerEvents::CREATE_DOCUMENT, $event);

            // cache prototype
            $this->prototypes[$documentClass] = $document;
        }

        return clone $this->prototypes[$documentClass];
    }
}