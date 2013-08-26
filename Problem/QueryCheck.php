<?php
/**
 * MAKEweb
 *
 * PHP Version 5
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @copyright   2009 brainbits GmbH (http://www.brainbits.net)
 * @version     SVN: $Id: Generator.php 2312 2007-01-25 18:46:27Z swentz $
 */

/**
 * Query check
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Stephan Wentz <sw@brainbits.net>
 * @copyright   2009 brainbits GmbH (http://www.brainbits.net)
 */
class MWF_Core_Indexer_Problem_QueryCheck implements MWF_Core_Problems_Check_Interface
{
    /**
     * Indexer
     *
     * @var MWF_Core_Indexer_Indexer_Query
     */
    protected $_indexerQuery;

    /**
     * Indexer search
     *
     * @var MWF_Core_Indexer_Indexer_Search
     */
    protected $_indexerSearch;

    /**
     * Global properties storage
     *
     * @var MWF_Core_Core_Properties
     */
    protected $_properties;

    /**
     * Constructor
     *
     * @param MWF_Core_Indexer_Query   $indexerQuery
     * @param MWF_Core_Indexer_Search  $indexerSearch
     * @param MWF_Core_Core_Properties $properties
     */
    public function __construct(MWF_Core_Indexer_Query $indexerQuery, MWF_Core_Indexer_Search $indexerSearch, MWF_Core_Core_Properties $properties)
    {
        $this->_indexerQuery = $indexerQuery;
        $this->_indexerSearch = $indexerSearch;
        $this->_properties = $properties;
    }

    /**
     * @see MWF_Core_Problems_Check_Interface::check()
     */
    public function check()
    {
        $problems = array();

        try
        {
            $queryString = $this->_properties->get('indexer', 'checkQuery');

            if (!$queryString)
            {
                $problem = new MWF_Core_Problems_Problem();
                $problem->id         = 'indexer_check_query_no_check_defined';
                $problem->checkClass = __CLASS__;
                $problem->iconCls    = 'm-indexer-component-icon';
                $problem->severity   = MWF_Core_Problems_Problem::SEVERITY_WARNING;
                $problem->msg        = 'No check query defined.';
                $problem->hint       = 'Enter a check query in the indexer administration panel.';

                $problems[] = $problem;
            }
            else
            {

                $this->_indexerQuery->parseInput($queryString);
                $results = $this->_indexerSearch->query($this->_indexerQuery);

                if (!count($results))
                {
                    $problem = new MWF_Core_Problems_Problem();
                    $problem->id         = 'indexer_check_query_no_result';
                    $problem->checkClass = __CLASS__;
                    $problem->iconCls    = 'm-indexer-component-icon';
                    $problem->severity   = MWF_Core_Problems_Problem::SEVERITY_WARNING;
                    $problem->msg        = 'Check query has zero results.';
                    $problem->hint       = 'Check if the indexer is running.';

                    $problems[] = $problem;
                }
            }
        }
        catch (Exception $e)
        {
            $problem = new MWF_Core_Problems_Problem();
            $problem->id         = 'indexer_check_query_exception';
            $problem->checkClass = __CLASS__;
            $problem->iconCls    = 'm-indexer-component-icon';
            $problem->severity   = MWF_Core_Problems_Problem::SEVERITY_WARNING;
            $problem->msg        = get_class($e) . ' occured: ' . $e->getMessage();

            $problems[] = $problem;
        }

        return $problems;
    }
}
