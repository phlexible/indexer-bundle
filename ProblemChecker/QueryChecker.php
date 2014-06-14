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

namespace Phlexible\IndexerComponent\ProblemChecker;

use Phlexible\GuiComponent\Properties\Properties;
use Phlexible\IndexerComponent\Query\QueryInterface;
use Phlexible\IndexerComponent\Search\Search;
use Phlexible\ProblemsComponent\Entity\Problem;
use Phlexible\ProblemsComponent\ProblemChecker\ProblemCheckerInterface;

/**
 * Query check
 *
 * @category    MWF
 * @package     MWF_Core_Indexer
 * @author      Stephan Wentz <sw@brainbits.net>
 * @copyright   2009 brainbits GmbH (http://www.brainbits.net)
 */
class QueryChecker implements ProblemCheckerInterface
{
    /**
     * Indexer
     *
     * @var QueryInterface
     */
    protected $indexerQuery;

    /**
     * Indexer search
     *
     * @var Search
     */
    protected $indexerSearch;

    /**
     * @var Properties
     */
    protected $properties;

    /**
     * @param Search         $indexerSearch
     * @param Properties     $properties
     */
    public function __construct(Search $indexerSearch, Properties $properties)
    {
        $this->indexerSearch = $indexerSearch;
        $this->properties = $properties;
    }

    /**
     * @see MWF_Core_Problems_Check_Interface::check()
     */
    public function check()
    {
        $problems = array();

        try {
            $queryString = $this->properties->get('indexer', 'checkQuery');

            if (!$queryString) {
                $problem = new Problem();
                $problem
                    ->setId('indexer_check_no_check_query_defined')
                    ->setCheckClass(__CLASS__)
                    ->setIconClass('p-indexer-component-icon')
                    ->setSeverity(Problem::SEVERITY_WARNING)
                    ->setMessage('No check query defined.')
                    ->setHint('Enter a check query in the indexer administration panel.')
                ;
                $problems[] = $problem;
            } else {
                $this->indexerQuery->parseInput($queryString);
                $results = $this->indexerSearch->query($this->indexerQuery);

                if (!count($results)) {
                    $problem = new Problem();
                    $problem
                        ->setId('indexer_check_no_result_for_check_query')
                        ->setCheckClass(__CLASS__)
                        ->setIconClass('p-indexer-component-icon')
                        ->setSeverity(Problem::SEVERITY_WARNING)
                        ->setMessage('Check query has zero results.')
                        ->setHint('Check if the indexer is running.')
                    ;
                    $problems[] = $problem;
                }
            }
        } catch (\Exception $e) {
            $problem = new Problem();
            $problem
                ->setId('indexer_check_query_exception')
                ->setCheckClass(__CLASS__)
                ->setIconClass('p-indexer-component-icon')
                ->setSeverity(Problem::SEVERITY_WARNING)
                ->setMessage(get_class($e) . ': ' . $e->getMessage())
            ;
            $problems[] = $problem;
        }

        return $problems;
    }
}
