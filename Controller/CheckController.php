<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Controller;

use Phlexible\IndexerBundle\Query\Query\QueryString;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Phlexible\GuiBundle\Response\ResultResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * Check controller
 *
 * @author Stephan Wentz <sw@brainbits.net>
 * @Route("/indexer/check")
 * @Security("is_granted('indexer')")
 */
class CheckController extends Controller
{
    /**
     * @param Request $request
     *
     * @return ResultResponse
     * @Route("/set", name="indexer_check_set")
     */
    public function setAction(Request $request)
    {
        $query = $request->request->get('query');

        if (!$query) {
            return new ResultResponse(false, 'Check query can\'t be empty.');
        }

        $properties = $this->get('properties');

        $properties->set('indexer', 'checkQuery', $query);

        return new ResultResponse(true, 'Check query set.');
    }

    /**
     * @return ResultResponse
     * @Route("/get", name="indexer_check_get")
     */
    public function getAction()
    {
        $properties = $this->get('properties');

        $checkString = $properties->get('indexer', 'checkQuery');

        return new ResultResponse(true, 'Check query loaded.', array('query' => $checkString));
    }

    /**
     * @return ResultResponse
     * @Route("/check", name="indexer_check")
     */
    public function checkAction()
    {
        $properties = $this->get('properties');

        $checkString = $properties->get('indexer', 'checkQuery');
        $data = array(
            'query' => $checkString,
        );

        $translator = $this->get('translator');

        if ($checkString) {
            $client = $this->get('phlexible_indexer_storage_solr.storage');
            $select = $client->createSelect()
                ->addDocumentType('media')
                ->addField('_identifier_')
                ->setQuery(new QueryString($checkString))
                ->setRows(1)
            ;
            $results = $client->select($select);

            $cnt = $results->getTotal();

            return new ResultResponse((bool) $cnt, $translator->transChoice('indexer.check_query_result', $cnt, array('%num%' => $cnt), 'gui'), $data);
        } else {
            return new ResultResponse(false, $translator->trans('indexer.no_check_query_defined', array(), 'gui'), $data);
        }
    }
}
