<?php
/**
 * Phlexible
 *
 * PHP Version 5
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @copyright   2011 brainbits GmbH (http://www.brainbits.net)
 */

/**
 * Indexer Tools
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Marco Fischer <mf@brainbits.net>
 * @copyright   2011 brainbits GmbH (http://www.brainbits.net)
 */
class MWF_Core_Indexer_Tools
{
    /**
     * @var MWF_Component_Manager
     */
    protected $_componentCallback = null;

    /**
     * Constructor
     *
     * @param MWF_Component_Callback $componentCallback
     */
    public function __construct(MWF_Component_Callback $componentCallback)
    {
        $this->_componentCallback = $componentCallback;
    }

    public function getRepositoriesByAcceptedStorage($documentType)
    {
        $storages = $this->_componentCallback->getIndexerStorages();

        return self::filterRepositoriesByAcceptedStorage($documentType, $storages);
    }

    /**
     * Get storages for search
     *
     * @param MWF_Core_Indexer_Query_Interface $query
     */
    public function getRepositoriesForSearch(MWF_Core_Indexer_Query_Interface $query)
    {
        $documentTypes = $query->getDocumentTypes();
        $queryClassName = get_class($query);

        $storages = $this->_componentCallback->getIndexerStorages();

        $ret = $this->filterRepositoriesByAcceptedStorage($documentTypes, $storages);

        return $this->filterRepositoriesByAcceptedQuery($queryClassName, $ret);
    }

    public function filterRepositoriesByAcceptedQuery($queryClassNames, $storages)
    {
        $queryClassNames = (array) $queryClassNames;

        $result = array();
        foreach ($queryClassNames as $queryClassName)
        {
            try
            {
                $reflectionClass = new ReflectionClass($queryClassName);

                foreach ($storages as $id => $storage)
                {
                    foreach ($storage->getAcceptQuery() as $acceptQueryClassName)
                    {
                        if ($acceptQueryClassName === $queryClassName ||
                            $reflectionClass->isSubclassOf($acceptQueryClassName))
                        {
                            $result[$id] = $storage;
                        }
                    }
                }
            }
            catch (Exception $e)
            {
                MWF_Log::exception($e);
            }
        }

        return $result;
    }

    public function filterRepositoriesByAcceptedStorage($documentTypes, $storages)
    {
        $documentTypes = (array) $documentTypes;
        $result = array();

        $mappings = $this->_componentCallback->getIndexerStorageMappings();

        foreach ($documentTypes as $documentType)
        {
            if (isset($mappings[$documentType]))
            {
                $prefferedStoradeId = $mappings[$documentType];
                if (isset($storages[$prefferedStoradeId]))
                {
                    $result[$prefferedStoradeId] = $storages[$prefferedStoradeId];
                }

                continue;
            }

            foreach ($storages as $id => $storage)
            {
                $acceptedDocumentTypes = $storage->getAcceptStorage();

                if (!count($acceptedDocumentTypes) ||
                    count(array_intersect($documentTypes, $acceptedDocumentTypes)))
                {
                    $result[$id] = $storage;
                }
            }
        }

        return $result;
    }
}