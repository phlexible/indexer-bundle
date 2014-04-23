<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Event;

use Phlexible\IndexerComponent\Query\QueryInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Query event
 *
 * @author Phillip Look <plook@brainbits.net>
 */
abstract class QueryEvent extends Event
{
    /**
     * @var QueryInterface
     */
    private $query = null;

    /**
     * @param QueryInterface $query
     */
    public function __construct(QueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @return QueryInterface
     */
    public function getQuery()
    {
        return $this->query;
    }
}
