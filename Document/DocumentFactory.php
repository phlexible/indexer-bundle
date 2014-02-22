<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Document;

use Phlexible\Event\EventDispatcher;

/**
 * Document factory
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class DocumentFactory
{
    /**
     * @var EventDispatcher
     */
    protected $dispatcher = null;

    /**
     * @var array
     */
    protected $prototypes = array();

    /**
     * @var \Zend_Filter_Interface
     */
    protected $fieldNameFilter;

    /**
     * @param EventDispatcher        $dispatcher
     * @param \Zend_Filter_Interface $fieldNameFilter
     */
    public function __construct(EventDispatcher $dispatcher,
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
     * @return DocumentInterface
     */
    public function factory($documentClass, $documentType)
    {
        $prototypeKey = $documentClass . '___' . $documentType;

        if (!isset($this->prototypes[$prototypeKey]))
        {
            // create document
            $document = new $documentClass($documentType);

            // apply field name filter
            if ($this->fieldNameFilter && method_exists($document, 'setFieldNameFilter'))
            {
                $document->setFieldNameFilter($this->fieldNameFilter);
            }

            // fire create event
            #$event = new CreateDocumentEvent($document);
            #$this->dispatcher->dispatch($event);

            // cache prototype
            $this->prototypes[$prototypeKey] = $document;
        }

        return clone $this->prototypes[$prototypeKey];
    }
}