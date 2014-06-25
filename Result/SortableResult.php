<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Result;

use Phlexible\Bundle\IndexerBundle\Document\DocumentInterface;
use Phlexible\Bundle\IndexerBundle\Result\Sorter\SorterInterface;

/**
 * Indexer result
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class SortableResult extends Result
{
    /**
     * @var SorterInterface
     */
    private $sorter = null;

    /**
     * @param SorterInterface $sorter
     */
    public function __construct(SorterInterface $sorter = null)
    {
        if ($sorter) {
            $this->setSorter($sorter);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setSorter(SorterInterface $sorter)
    {
        $this->sorter = $sorter;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSorter()
    {
        return $this->sorter;
    }

    /**
     * {@inheritdoc}
     */
    public function sort()
    {
        $this->setDocuments($this->sorter->sort($this->getDocuments()));

        return $this;
    }

    /**
     * @param int $limit
     * @param int $current
     * @param int $range
     *
     * @return \Zend_Paginator
     */
    public function getPaginator($limit, $current = 0, $range = 5)
    {
        $paginator = \Zend_Paginator::factory($this->getDocuments());
        $paginator->setItemCountPerPage($limit);
        $paginator->setCurrentPageNumber($current);
        $paginator->setPageRange($range);

        return $paginator;
    }
}