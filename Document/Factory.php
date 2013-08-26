<?php
/**
 * Phlexible
 *
 * PHP Version 5
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */

/**
 * Document Factory
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
class MWF_Core_Indexer_Document_Factory
{
    /**
     * @var Brainbits_Event_Dispatcher
     */
    protected $_dispatcher = null;

    /**
     * @var array
     */
    protected $_prototypes = array();

    /**
     * @var Zend_Filter_Interface
     */
    protected $_fieldNameFilter;


    /**
     * Constructor
     *
     * @param Brainbits_Event_Dispatcher $dispatcher
     * @param Zend_Filter_Interface      $fieldNameFilter (optional)
     */
    public function __construct(Brainbits_Event_Dispatcher $dispatcher,
                                Zend_Filter_Interface      $fieldNameFilter = null)
    {
        $this->_dispatcher      = $dispatcher;
        $this->_fieldNameFilter = $fieldNameFilter;
    }

    /**
     * Document factory
     *
     * @param string $documentClass
     * @param string $documentType
     *
     * @return MWF_Core_Indexer_Document_Interface
     */
    public function factory($documentClass, $documentType)
    {
        $prototypeKey = $documentClass . '___' . $documentType;

        if (!isset($this->_prototypes[$prototypeKey]))
        {
            // create document
            $document = new $documentClass($documentType);

            // apply field name filter
            if ($this->_fieldNameFilter && method_exists($document, 'setFieldNameFilter'))
            {
                $document->setFieldNameFilter($this->_fieldNameFilter);
            }

            // fire create event
            $event = new MWF_Core_Indexer_Event_CreateDocument($document);
            $this->_dispatcher->postNotification($event);

            // cache prototype
            $this->_prototypes[$prototypeKey] = $document;
        }

        return clone $this->_prototypes[$prototypeKey];
    }
}