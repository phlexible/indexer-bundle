<?php
/**
 * MAKEweb
 *
 * PHP Version 5
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @copyright   2009 brainbits GmbH (http://www.brainbits.net)
 * @version     0.0.1
 */

/**
 * Data Controller
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Stephan Wentz <sw@brainbits.net>
 * @copyright   2009 brainbits GmbH (http://www.brainbits.net)
 */
class Indexer_DataController extends MWF_Controller_Action
{
    public function setcheckAction()
    {
        $query = $this->_getParam('query');

        if ($query)
        {
            $container = $this->getContainer();
            $properties = $container->properties;

            $checkString = $properties->set('indexer', 'checkQuery', $this->_getParam('query'));

            $result = MWF_Ext_Result::encode(true, 0, 'Check query set.');
        }
        else
        {
            $result = MWF_Ext_Result::encode(false, 0, 'Check query can\'t be empty.');
        }

        $this->_response->setAjaxPayload($result);
    }

    public function getcheckAction()
    {
        $container = $this->getContainer();
        $properties = $container->properties;

        $checkString = $properties->get('indexer', 'checkQuery');

        $result = MWF_Ext_Result::encode(true, 0, 'Check query loaded.', array('query' => $checkString));

        $this->_response->setAjaxPayload($result);
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
                $result = MWF_Ext_Result::encode(true, 0, $t9n->check_query_succeeded($cnt), $data);
            }
            else
            {
                $result = MWF_Ext_Result::encode(false, 0, $t9n->check_query_failed, $data);
            }
        }
        else
        {
            $result = MWF_Ext_Result::encode(false, 0, $t9n->no_check_query_defined, $data);
        }

        $this->_response->setAjaxPayload($result);
    }

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
