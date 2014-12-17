<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Query\Suggest;

/**
 * Term suggest
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class TermSuggest extends AbstractSuggest
{
    const SORT_SCORE = 'score';
    const SORT_FREQUENCY = 'frequency';

    const SUGGEST_MODE_MISSING = 'missing';
    const SUGGEST_MODE_POPULAR = 'popular';
    const SUGGEST_MODE_ALWAYS = 'always';

    /**
     * @param string $analyzer
     *
     * @return $this
     */
    public function setAnalyzer($analyzer)
    {
        return $this->setParam("analyzer", $analyzer);
    }

    /**
     * See SORT_* constants for options
     *
     * @param string $sort
     *
     * @return $this
     */
    public function setSort($sort)
    {
        return $this->setParam("sort", $sort);
    }

    /**
     * See SUGGEST_MODE_* constants for options
     *
     * @param string $mode
     *
     * @return $this
     */
    public function setSuggestMode($mode)
    {
        return $this->setParam("suggestMode", $mode);
    }

    /**
     * If true, suggest terms will be lower cased after text analysis
     *
     * @param bool $lowercase
     *
     * @return $this
     */
    public function setLowercaseTerms($lowercase = true)
    {
        return $this->setParam("lowercaseTerms", (bool) $lowercase);
    }

    /**
     * Set the maximum edit distance candidate suggestions can have in order to be considered as a suggestion
     * Either 1 or 2. Any other value will result in an error.
     *
     * @param int $max
     *
     * @return $this
     */
    public function setMaxEdits($max)
    {
        return $this->setParam("maxEdits", (int) $max);
    }

    /**
     * The number of minimum prefix characters that must match in order to be a suggestion candidate
     * Defaults to 1.
     *
     * @param int $length
     *
     * @return $this
     */
    public function setPrefixLength($length)
    {
        return $this->setParam("prefixLength", (int) $length);
    }

    /**
     * The minimum length a suggest text term must have in order to be included.
     * Defaults to 4.
     *
     * @param int $length
     *
     * @return $this
     */
    public function setMinWordLength($length)
    {
        return $this->setParam("minWordLen", (int) $length);
    }

    /**
     * Defaults to 5.
     *
     * @param int $max
     *
     * @return $this
     */
    public function setMaxInspections($max)
    {
        return $this->setParam("maxInspections", $max);
    }

    /**
     * Set the minimum number of documents in which a suggestion should appear.
     * Defaults to 0. If the value is greater than 1, it must be a whole number.
     *
     * @param int|float $min
     *
     * @return $this
     */
    public function setMinDocFrequency($min)
    {
        return $this->setParam("minDocFreq", $min);
    }

    /**
     * Set the maximum number of documents in which a suggest text token can exist in order to be included.
     *
     * @param float $max
     *
     * @return $this
     */
    public function setMaxTermFrequency($max)
    {
        return $this->setParam("maxTermFreq", $max);
    }
}