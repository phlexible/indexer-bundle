<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Controller;

use Phlexible\IndexerBundle\Query\Query\QueryString;
use Phlexible\IndexerBundle\Query\Query\TermsQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Search controller
 *
 * @author Stephan Wentz <sw@brainbits.net>
 * @Route("/indexer/search")
 * @Security("is_granted('indexer')")
 */
class SearchController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @Route("", name="indexer_search")
     */
    public function searchAction(Request $request)
    {
        $start       = $request->get('start', 0);
        $limit       = $request->get('limit', 20);
        $queryString = $request->get('query');
        $language    = $request->get('language', 'de');

        $client = $this->get('phlexible_indexer_storage_solr.storage');
        $select = $client->createSelect()
            ->addDocumentType('media')
            ->addField('title')
            ->setQuery(new QueryString($queryString))
            ->setStart($start)
            ->setRows($limit)
        ;
        $results = $client->select($select);

        $data = array();
        foreach ($results as $result) {
            $data[] = array(
                'id'       => $result->getIdentifier(),
                'lang'     => $result->hasField('language') ? $result->getValue('language') : '',
                'tid'      => $result->hasField('tid') ? $result->getValue('tid') : '',
                'eid'      => $result->hasField('eid') ? $result->getValue('eid') : '',
                'title'    => $result->getValue('title'),
                'content'  => $result->getValue('copy'),
                'online'   => 1,
                'document' => get_class($result),
            );
        }

        return new JsonResponse(
            array(
                'docs'  => $data,
                'total' => $results->getTotal(),
            )
        );
    }

    /**
     * @param string $queryString
     *
     * @return array
     */
    private function search($queryString)
    {
    }
}
