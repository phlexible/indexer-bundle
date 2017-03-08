<?php

/*
 * This file is part of the phlexible indexer package.
 *
 * (c) Stephan Wentz <sw@brainbits.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phlexible\Bundle\IndexerBundle\ProblemChecker;

use Phlexible\Bundle\IndexerBundle\Indexer\IndexerCollection;
use Phlexible\Bundle\ProblemBundle\Entity\Problem;
use Phlexible\Bundle\ProblemBundle\ProblemChecker\ProblemCheckerInterface;

/**
 * Indexer checker.
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class IndexerChecker implements ProblemCheckerInterface
{
    /**
     * @var IndexerCollection $indexers
     */
    private $indexers;

    /**
     * @param IndexerCollection $indexers
     */
    public function __construct(IndexerCollection $indexers)
    {
        $this->indexers = $indexers;
    }

    public function check()
    {
        $problems = array();

        try {
            foreach ($this->indexers as $indexer) {
                echo get_class($indexer)." ".count($indexer).PHP_EOL;
                if (!count($indexer)) {
                    $problem = new Problem();
                    $problem
                        ->setId('indexer_'.$indexer->getType().'_no_documents')
                        ->setCheckClass(__CLASS__)
                        ->setIconClass('p-indexer-component-icon')
                        ->setSeverity(Problem::SEVERITY_WARNING)
                        ->setMessage('No documents in index '.$indexer->getType().'.')
                        ->setHint('Execute indexer run.')
                    ;
                    $problems[] = $problem;
                }
            }
        } catch (\Exception $e) {
            $problem = new Problem();
            $problem
                ->setId('indexer_problem_check_exception')
                ->setCheckClass(__CLASS__)
                ->setIconClass('p-indexer-component-icon')
                ->setSeverity(Problem::SEVERITY_WARNING)
                ->setMessage(get_class($e).': '.$e->getMessage())
            ;
            $problems[] = $problem;
        }

        return $problems;
    }
}
