<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Query\Facet;

use Phlexible\Bundle\IndexerBundle\Query\Query\QueryInterface;

/**
 * Query facet
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class QueryFacet extends AbstractFacet
{
    /**
     * Set the query for the facet.
     *
     * @param QueryInterface $query
     *
     * @return $this
     */
    public function setQuery(QueryInterface $query)
    {
        return $this->setParam('query', $query);
    }
}
