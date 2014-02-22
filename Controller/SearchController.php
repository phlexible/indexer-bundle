<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerComponent\Controller;

use Phlexible\CoreComponent\Controller\Action\Action;

/**
 * Search controller
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class SearchController extends Action
{
    public function searchAction()
    {
        $start       = $this->_getParam('start');
        $limit       = $this->_getParam('limit');
        $queryString = $this->_getParam('query');
        $language    = $this->_getParam('language', 'de');

        $data = $this->_getResults($queryString);

        $this->_response->setAjaxPayload(
            array(
                'docs'  => array_slice($data, $start, $limit),
                'total' => count($data),
            )
        );
    }

    protected function _getResults($queryString)
    {
        $container     = $this->getContainer();
        $query         = $container->indexerQuery;
        $indexerSearch = $container->indexerSearch;

        $query->parseInput($queryString);
        $results = $indexerSearch->query($query);

        $data = array();
        foreach ($results as $result)
        {
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
