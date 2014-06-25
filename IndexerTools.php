<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle;

use Phlexible\Component\ComponentCallback;
use Phlexible\Bundle\IndexerBundle\Storage\StorageCollection;

/**
 * Indexer tools
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class IndexerTools
{
    /**
     * @var array
     */
    protected $componentCallback;

    /**
     * @var array
     */
    protected $storages = array();

    /**
     * @param ComponentCallback $componentCallback
     * @param StorageCollection $storages
     */
    public function __construct(ComponentCallback $componentCallback, StorageCollection $storages)
    {
        $this->componentCallback = $componentCallback;

        foreach ($storages as $storage) {
            $this->storages[$storage->getId()] = $storage;
        }
    }

    /**
     * @param string $documentType
     * @return array
     */
    public function getRepositoriesByAcceptedStorage($documentType)
    {
        return self::filterRepositoriesByAcceptedStorage($documentType, $this->storages);
    }

    /**
     * Get storages for search
     *
     * @param MWF_Core_Indexer_Query_Interface $query
     * @return array
     */
    public function getRepositoriesForSearch(MWF_Core_Indexer_Query_Interface $query)
    {
        $documentTypes = $query->getDocumentTypes();
        $queryClassName = get_class($query);

        $ret = $this->filterRepositoriesByAcceptedStorage($documentTypes, $this->storages);

        return $this->filterRepositoriesByAcceptedQuery($queryClassName, $ret);
    }

    /**
     * @param string $queryClassNames
     * @param array  $storages
     * @return array
     */
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

    /**
     * @param array $documentTypes
     * @param array $storages
     * @return array
     */
    public function filterRepositoriesByAcceptedStorage($documentTypes, $storages)
    {
        $documentTypes = (array) $documentTypes;
        $result = array();

        $mappings = $this->componentCallback->getIndexerStorageMappings();

        foreach ($documentTypes as $documentType)
        {
            if (isset($mappings[$documentType]))
            {
                $preferredStorageId = $mappings[$documentType];
                if (isset($storages[$preferredStorageId]))
                {
                    $result[$preferredStorageId] = $storages[$preferredStorageId];
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
