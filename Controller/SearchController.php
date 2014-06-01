<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Controller;

use Phlexible\CoreComponent\Controller\Action\Action;
use Phlexible\CoreComponent\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Search controller
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class SearchController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function searchAction(Request $request)
    {
        $start       = $request->get('start');
        $limit       = $request->get('limit');
        $queryString = $request->get('query');
        $language    = $request->get('language', 'de');

        $data = $this->_getResults($queryString);

        return new JsonResponse(
            array(
                'docs'  => array_slice($data, $start, $limit),
                'total' => count($data),
            )
        );
    }

    /**
     * @param string $queryString
     *
     * @return array
     */
    protected function _getResults($queryString)
    {
        $query         = $this->get('indexer.query');
        $indexerSearch = $this->get('indexer.search');

        $query->parseInput($queryString);
        $results = $indexerSearch->query($query);

        $data = array();
        foreach ($results as $result) {
            $data[] = array(
                'id'      => $result->getIdentifier(),
                'lang'    => $result->hasField('language') ? $result->getValue('language') : '',
                'tid'     => $result->hasField('tid') ? $result->getValue('tid') : '',
                'eid'     => $result->hasField('eid') ? $result->getValue('eid') : '',
                'title'   => $result->getValue('title'),
                'content' => $result->getValue('copy'),
                'online'  => 1,
                'node'    => get_class($result),
            );
        }

        return $data;
    }
}
