<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Storage\SuggestQuery;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Suggest query
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class SuggestQuery
{
    /**
      * @var EventDispatcherInterface
      */
    protected $dispatcher;

    /**
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
}
