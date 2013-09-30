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
 * Indexer Component
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2010 brainbits GmbH (http://www.brainbits.net)
 */
class MWF_Core_Indexer_Component extends MWF_Component_Abstract
{
    const RESOURCE_INDEXER = 'indexer';

    /**
     * Constructor
     * Initialses the Component values
     */
    public function __construct()
    {
        $this->setVersion('0.7.0');
        $this->setId('indexer');
        $this->setFile(__FILE__);
        $this->setPackage('mwf');
    }

    public function initContainer(MWF_Container_ContainerBuilder $container)
    {
        $container->addComponents(
            array(
                'indexerDocumentFieldNameFilterCamelCaseToUnderscore' => array(
                    'class'     => 'Zend_Filter_Word_CamelCaseToUnderscore',
                    'scope'     => 'singleton',
                ),
                'indexerDocumentFieldNameFilterStringToLower' => array(
                    'class'     => 'Zend_Filter_StringToLower',
                    'scope'     => 'singleton',
                ),
                'indexerDocumentFieldNameFilter' => array(
                    'class'     => 'Zend_Filter',
                    'methods' => array(
                        array(
                            'method' => 'addFilter',
                            'arguments' => array(
                                'indexerDocumentFieldNameFilterCamelCaseToUnderscore',
                            ),
                        ),
                        array(
                            'method' => 'addFilter',
                            'arguments' => array(
                                'indexerDocumentFieldNameFilterStringToLower',
                            ),
                        ),
                    ),
                    'scope'     => 'singleton',
                ),
                'indexerDocumentFactory' => array(
                    'class'     => 'MWF_Core_Indexer_Document_Factory',
                    'arguments' => array('dispatcher', 'indexerDocumentFieldNameFilter'),
                    'scope'     => 'singleton',
                ),
                'indexerTools' => array(
                    'class'     => 'MWF_Core_Indexer_Tools',
                    'arguments' => array('componentCallback'),
                    'scope'     => 'singleton',
                ),
                'indexerSearch' => array(
                    'class'     => 'MWF_Core_Indexer_Search',
                    'arguments' => array('componentManager', 'indexerTools', 'indexerResult'),
                    'scope'     => 'prototype',
                ),
                'indexerQueryParser' => array(
                    'class'     => 'MWF_Core_Indexer_Query_Parser',
                    'scope'     => 'prototype',
                ),
                'indexerResultSorterRelevance' => array(
                    'class'     => 'MWF_Core_Indexer_Result_Sorter_Relevance',
                    'scope'     => 'prototype',
                ),
                'indexerResultSorterRelevanceReverse' => array(
                    'class'     => 'MWF_Core_Indexer_Result_Sorter_RelevanceReverse',
                    'scope'     => 'prototype',
                ),
                'indexerResultSorterField' => array(
                    'class'     => 'MWF_Core_Indexer_Result_Sorter_Field',
                    'arguments' => array(
                        ':indexerResultSorterRelevance.compareField',
                        ':indexerResultSorterRelevance.caseSensitive'
                    ),
                    'scope'     => 'prototype',
                ),
                'indexerResultSorterFieldReverse' => array(
                    'class'     => 'MWF_Core_Indexer_Result_Sorter_FieldReverse',
                    'arguments' => array(':indexerResultSorterRelevance.compareField'),
                    'scope'     => 'prototype',
                ),
                'indexerResult' => array(
                    'class'     => 'MWF_Core_Indexer_Result',
                    'arguments' => array('indexerResultSorterRelevance'),
                    'scope'     => 'prototype',
                ),
                'indexerQuery' => array(
                    'class'     => 'MWF_Core_Indexer_Query',
                    'arguments' => array('indexerQueryParser', 'indexerBoost')
                ),
                'indexerBoost' => array(
                    'class'     => 'MWF_Core_Indexer_Boost',
                ),
                'indexerQueryCheck' => array(
                    'class'     => 'MWF_Core_Indexer_Problem_QueryCheck',
                    'arguments' => array('indexerQuery', 'indexerSearch', 'properties')
                ),
                // commands
                'indexerCommandBuild' => array(
                    'class' => 'MWF_Core_Indexer_Command_Build',
                    'tag' => 'command',
                ),
                'indexerCommandFlush' => array(
                    'class' => 'MWF_Core_Indexer_Command_Flush',
                    'tag' => 'command',
                ),
                'indexerCommandList' => array(
                    'class' => 'MWF_Core_Indexer_Command_List',
                    'tag' => 'command',
                ),
                'indexerCommandMaintenance' => array(
                    'class' => 'MWF_Core_Indexer_Command_Maintenance',
                    'tag' => 'command',
                ),
                'indexerCommandQuery' => array(
                    'class' => 'MWF_Core_Indexer_Command_Query',
                    'tag' => 'command',
                ),
            )
        );
    }

    public function init()
    {
        $container = $this->getContainer();

        $callback = $container->componentCallback;
        $callback
            ->register(
                'getIndexers',
                array(
                    MWF_Component_Callback::CONFIG_CONTAINER_KEYS => true,
                )
            )
            ->register(
                'getIndexerStorages',
                array(
                    MWF_Component_Callback::CONFIG_CONTAINER_KEYS => true,
                )
            )
            ->register(
                'getIndexerSearches',
                array(
                    MWF_Component_Callback::CONFIG_CONTAINER_KEYS => true,
                )
            )
            ->register('getIndexerStorageMappings');
    }

    /**
     * ACL Callback
     *
     * Returns all ACL resources this component provides
     *
     * @return array
     */
    public function getAcl()
    {
        return array(
            array(
                'roles' => array(
                ),
                'resources' => array(
                    self::RESOURCE_INDEXER,
                ),
                'allow' => array(
                )
            )
        );
    }

    public function getAdminViews()
    {
        $views = array();

        $item = new MWF_Core_Menu_Item_Panel();
        $item->setText('Indexer')
             ->setIconClass('m-indexer-component-icon')
             ->setPanel('MWF.core.indexer.MainPanel')
             ->setCheck(self::RESOURCE_INDEXER);

        $views[] = $item;

        return $views;
    }

    public function getProblemChecks()
    {
        $checks = array(
            'indexerQueryCheck',
        );

        return $checks;
    }

    public function getRoutes()
    {
        return array(
            'indexer_search' => new MWF_Controller_Router_Route(
                '/indexer/data/:action/*',
                array('module' => 'indexer', 'controller' => 'data', 'action' => 'search'),
                array('_resource' => self::RESOURCE_INDEXER)
            ),
        );
    }

    public function getScripts()
    {
        $path = $this->getPath() . '_scripts/';

        return array(
            $path . 'Definitions.js',
            #$path . 'SearchPanel.js',
            $path . 'MainPanel.js',
        );
    }

    public function getTranslations()
    {
        $t9n  = $this->getContainer()->t9n;
        $page = $t9n->indexer->toArray();

        return array(
            'MWF.strings.Indexer' => $page
        );
    }
}
