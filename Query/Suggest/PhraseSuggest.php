<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Query\Suggest;

/**
 * Phrase suggest
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class PhraseSuggest extends AbstractSuggest
{
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
     * Set the max size of the n-grams (shingles) in the field
     *
     * @param int $size
     *
     * @return $this
     */
    public function setGramSize($size)
    {
        return $this->setParam("gramSize", $size);
    }

    /**
     * Set the likelihood of a term being misspelled even if the term exists in the dictionary
     * Defaults to 0.95, meaning 5% of the words are misspelled.
     *
     * @param float $likelihood
     *
     * @return $this
     */
    public function setRealWorldErrorLikelihood($likelihood)
    {
        return $this->setParam("realWorldErrorLikelihood", $likelihood);
    }

    /**
     * Set the factor applied to the input phrases score to be used as a threshold for other suggestion candidates.
     * Only candidates which score higher than this threshold will be included in the result.
     * Defaults to 1.0.
     *
     * @param float $confidence
     *
     * @return $this
     */
    public function setConfidence($confidence)
    {
        return $this->setParam("confidence", $confidence);
    }

    /**
     * Set the maximum percentage of the terms considered to be misspellings in order to form a correction
     *
     * @param float $max
     *
     * @return $this
     */
    public function setMaxErrors($max)
    {
        return $this->setParam("maxErrors", $max);
    }

    /**
     * @param string $separator
     *
     * @return $this
     */
    public function setSeparator($separator)
    {
        return $this->setParam("separator", $separator);
    }

    /**
     * Set suggestion highlighting
     *
     * @param string $preTag
     * @param string $postTag
     *
     * @return $this
     */
    public function setHighlight($preTag, $postTag)
    {
        return $this->setParam("highlight", array(
            'pre_tag' => $preTag,
            'post_tag' => $postTag
        ));
    }

    /**
     * @param float $discount
     *
     * @return $this
     */
    public function setStupidBackoffSmoothing($discount = 0.4)
    {
        return $this->setSmoothingModel("stupid_backoff", array(
            "discount" => $discount
        ));
    }

    /**
     * @param float $alpha
     *
     * @return $this
     */
    public function setLaplaceSmoothing($alpha = 0.5)
    {
        return $this->setSmoothingModel("laplace", array(
            "alpha" => $alpha
        ));
    }

    /**
     * @param float $trigramLambda
     * @param float $bigramLambda
     * @param float $unigramLambda
     *
     * @return $this
     */
    public function setLinearInterpolationSmoothing($trigramLambda, $bigramLambda, $unigramLambda)
    {
        return $this->setSmoothingModel("linear_interpolation", array(
            "trigram_lambda" => $trigramLambda,
            "bigram_lambda"  => $bigramLambda,
            "unigram_lambda" => $unigramLambda
        ));
    }

    /**
     * @param string $model
     * @param array  $params
     *
     * @return $this
     */
    public function setSmoothingModel($model, array $params)
    {
        return $this->setParam("smoothing", array(
            $model => $params
        ));
    }
}