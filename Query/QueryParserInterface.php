<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Query;

/**
 * Query parser interface
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
interface QueryParserInterface
{
    /**
     * Parse query
     *
     * @param string $input
     */
    public function parse($input);
}