<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Search;

use Phlexible\Bundle\IndexerBundle\Query\QueryInterface;
use Phlexible\Bundle\IndexerBundle\Result\ResultInterface;
use Phlexible\Bundle\IndexerBundle\Result\Sorter\SorterInterface;

/**
 * Search interface
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
interface SearchInterface
{
    const QUERY_FIRST = 'query_first';
    const QUERY_ALL   = 'query_all';
    const QUERY_BEST  = 'query_best';

    /**
     * @param QueryInterface $query
     * @return ResultInterface $indexerResult
     */
    public function query(QueryInterface $query);

    /**
     * @param SorterInterface $sorter
     */
    public function setSorter(SorterInterface $sorter);

}