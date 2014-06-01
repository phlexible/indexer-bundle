<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Controller;

use Phlexible\CoreComponent\Controller\Controller;
use Phlexible\CoreComponent\Response\ResultResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Check controller
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class CheckController extends Controller
{
    /**
     * @param Request $request
     *
     * @return ResultResponse
     */
    public function setAction(Request $request)
    {
        $query = $request->query->get('query');

        if (!$query) {
            return new ResultResponse(true, 'Check query can\'t be empty.');
        }

        $properties = $this->get('properties');

        $properties->set('indexer', 'checkQuery', $query);

        return new ResultResponse(true, 'Check query set.');
    }

    /**
     * @return ResultResponse
     */
    public function getAction()
    {
        $properties = $this->get('properties');

        $checkString = $properties->get('indexer', 'checkQuery');

        return new ResultResponse(true, 'Check query loaded.', array('query' => $checkString));
    }

    /**
     * @return ResultResponse
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
            $results = $this->_getResults($checkString);

            $cnt = count($results);
            if ($cnt) {
                return new ResultResponse(true, $translator->translate('indexer.check_query_succeeded', $cnt), $data);
            } else {
                return new ResultResponse(false, $translator->translate('indexer.check_query_failed'), $data);
            }
        } else {
            return new ResultResponse(false, $translator->get('indexer.no_check_query_defined'), $data);
        }
    }
}
