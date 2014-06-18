<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Query\Query;

/**
 * Query string
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class QueryString implements QueryInterface
{
    /**
     * @var string
     */
    private $query;

    /**
     * @param string $query
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }
}