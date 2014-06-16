<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Query;

/**
 * Query parser
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class QueryParser implements QueryParserInterface
{
    /**
     * @var array
     */
    protected $_positiveTerms = array();

    /**
     * @var array
     */
    protected $_negativeTerms = array();

    public function addTermPositive($term, $quote = true)
    {
        if ($quote)
        {
            $this->_positiveTerms[] = '"' . $term .'"';
        }
        else
        {
            $this->_positiveTerms[] = $term;
        }
    }

    public function addTermNegative($term, $quote = true)
    {
        if ($quote)
        {
            $this->_negativeTerms[] = '"' . $term .'"';
        }
        else
        {
            $this->_negativeTerms[] = $term;
        }
    }

    public function getPositiveTerms()
    {
        return $this->_positiveTerms;
    }

    public function getNegativeTerms()
    {
        return $this->_negativeTerms;
    }

    public function parse($input)
    {
        $this->_negativeTerms = array();
        $this->_positiveTerms = array();

        $forbidden = array('and', 'or');

        $terms = array();

        $lookForClosure = false;

        $operator = false;

        foreach (explode(' ', $input) as $token)
        {
            $term = trim($token);

            if (strlen($term) == 0)
            {
                continue;
            }

            $lowerTerm = strtolower($term);

            if (false === $lookForClosure && in_array($lowerTerm, $forbidden))
            {
                continue;
            }

            if (false === $lookForClosure && $lowerTerm == 'not')
            {
                $operator = '-';
                continue;
            }

            if ('+' === substr($term, 0, 1))
            {
                if ($operator === false)
                {
                    $operator = '+';
                }
                $term = substr($term, 1);

                if (empty($term))
                {
                    continue;
                }
            }
            elseif ('-' === substr($term, 0, 1))
            {
                if ($operator === false)
                {
                    $operator = '-';
                }
                $term = substr($term, 1);

                if (empty($term))
                {
                    continue;
                }
            }
            elseif ($operator === false)
            {
                $operator = '+';
            }

            if ('"' === substr($term, 0, 1))
            {
                $lookForClosure = array($term);
            }

            if ($lookForClosure !== false)
            {
                if ($lookForClosure[0] != $term)
                {
                    $lookForClosure[] = $term;
                }

                if ('"' === substr($term, -1))
                {
                    $term = implode(' ', $lookForClosure);
                    $lookForClosure = false;
                }
            }

            if ('"' === substr($term, 0, 1))
            {
                $term = substr($term, 1);
            }

            if ('"' === substr($term, -1))
            {
                $term = substr($term, 0, strlen($term)-1);
            }

            if ($lookForClosure === false)
            {
                if ($operator == '-')
                {
                    $this->addTermNegative($term, false);
                }
                else
                {
                    $this->addTermPositive($term, false);
                }
                $operator = false;
            }
        }

        // if closure not closed add open closure to terms
        if (false !== $lookForClosure)
        {
            $term = implode(' ', $lookForClosure);
            $lookForClosure = false;

            if ('"' === substr($term, 0, 1))
            {
                $term = substr($term, 1);
            }

            if ($operator == '-')
            {
                $this->addTermNegative($term, false);
            }
            else
            {
                $this->addTermPositive($term, false);
            }
            $operator = false;
        }
    }
}