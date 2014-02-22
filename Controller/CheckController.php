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
 * Check controller
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class CheckController extends Action
{
    public function setAction()
    {
        $query = $this->_getParam('query');

        if ($query)
        {
            $container = $this->getContainer();
            $properties = $container->properties;

            $properties->set('indexer', 'checkQuery', $this->_getParam('query'));

            $this->_response->setResult(true, 0, 'Check query set.');
        }
        else
        {
            $this->_response->setResult(false, 0, 'Check query can\'t be empty.');
        }
    }

    public function getAction()
    {
        $container = $this->getContainer();
        $properties = $container->properties;

        $checkString = $properties->get('indexer', 'checkQuery');

        $this->_response->setResult(true, 0, 'Check query loaded.', array('query' => $checkString));
    }

    public function checkAction()
    {
        $container = $this->getContainer();
        $properties = $container->properties;

        $checkString = $properties->get('indexer', 'checkQuery');
        $data = array(
            'query' => $checkString,
        );

        $t9n = $container->t9n->indexer;

        if ($checkString)
        {
            $results = $this->_getResults($checkString);

            $cnt = count($results);
            if ($cnt)
            {
                $this->_response->setResult(true, 0, $t9n->check_query_succeeded($cnt), $data);
            }
            else
            {
                $this->_response->setResult(false, 0, $t9n->check_query_failed, $data);
            }
        }
        else
        {
            $this->_response->setResult(false, 0, $t9n->no_check_query_defined, $data);
        }
    }
}
