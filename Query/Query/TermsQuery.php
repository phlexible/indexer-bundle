<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\IndexerBundle\Query\Query;

/**
 * Terms query
 *
 * @author Marco Fischer <mf@brainbits.net>
 */
class TermsQuery implements QueryInterface
{
    /**
     * @var string
     */
    private $terms;

    /**
     * @param string $terms
     */
    public function __construct($terms)
    {
        $this->terms = $terms;
    }

    /**
     * @return string
     */
    public function getTerms()
    {
        return $this->terms;
    }

    /**
     * @return array
     */
    public function getPositiveTerms()
    {
        return $this->parse($this->terms)[0];
    }

    /**
     * @return array
     */
    public function getNegativeTerms()
    {
        return $this->parse($this->terms)[1];
    }

    /**
     * {@inheritdoc}
     */
    public function parse($input)
    {
        $positiveTerms = array();
        $negativeTerms = array();

        $forbidden = array('and', 'or');

        $lookForClosure = false;

        $operator = false;

        foreach (explode(' ', $input) as $token) {
            $term = trim($token);

            if (strlen($term) == 0) {
                continue;
            }

            $lowerTerm = strtolower($term);

            if (false === $lookForClosure && in_array($lowerTerm, $forbidden)) {
                continue;
            }

            if (false === $lookForClosure && $lowerTerm == 'not') {
                $operator = '-';
                continue;
            }

            if ('+' === substr($term, 0, 1)) {
                if ($operator === false) {
                    $operator = '+';
                }
                $term = substr($term, 1);

                if (empty($term)) {
                    continue;
                }
            } elseif ('-' === substr($term, 0, 1)) {
                if ($operator === false) {
                    $operator = '-';
                }
                $term = substr($term, 1);

                if (empty($term)) {
                    continue;
                }
            } elseif ($operator === false) {
                $operator = '+';
            }

            if ('"' === substr($term, 0, 1)) {
                $lookForClosure = array($term);
            }

            if ($lookForClosure !== false) {
                if ($lookForClosure[0] != $term) {
                    $lookForClosure[] = $term;
                }

                if ('"' === substr($term, -1)) {
                    $term = implode(' ', $lookForClosure);
                    $lookForClosure = false;
                }
            }

            if ('"' === substr($term, 0, 1)) {
                $term = substr($term, 1);
            }

            if ('"' === substr($term, -1)) {
                $term = substr($term, 0, strlen($term)-1);
            }

            if ($lookForClosure === false) {
                if ($operator == '-') {
                    $negativeTerms[] = $term;
                } else {
                    $positiveTerms[] = $term;
                }
                $operator = false;
            }
        }

        // if closure not closed add open closure to terms
        if (false !== $lookForClosure) {
            $term = implode(' ', $lookForClosure);
            $lookForClosure = false;

            if ('"' === substr($term, 0, 1)) {
                $term = substr($term, 1);
            }

            if ($operator == '-') {
                $negativeTerms[] = $term;
            } else {
                $positiveTerms[] = $term;
            }
            $operator = false;
        }

        return array($positiveTerms, $negativeTerms);
    }
}