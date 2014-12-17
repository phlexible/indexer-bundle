<?php
/**
 * phlexible
 *
 * @copyright 2007-2013 brainbits GmbH (http://www.brainbits.net)
 * @license   proprietary
 */

namespace Phlexible\Bundle\IndexerBundle\Query;

use Phlexible\Bundle\IndexerBundle\Query\Suggest\SuggestInterface;

/**
 * Suggest
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class Suggest extends QueryParam
{
    /**
     * @var SuggestInterface[]
     */
    private $suggestions = array();

    /**
     * @param SuggestInterface $suggestion
     */
    public function __construct(SuggestInterface $suggestion = null)
    {
        if (!is_null($suggestion)) {
            $this->addSuggestion($suggestion);
        }
    }


    /**
     * Set the global text for this suggester
     * @param string $text
     * @return \Elastica\Suggest
     */
    public function setGlobalText($text)
    {
        return $this->setParam("globalText", $text);
    }

    /**
     * Add a suggestion to this suggest clause
     *
     * @param SuggestInterface $suggestion
     *
     * @return \Elastica\Suggest
     */
    public function addSuggestion(SuggestInterface $suggestion)
    {
        $this->suggestions[$suggestion->getName()] = $suggestion;

        return $this;
    }

    /**
     * @return SuggestInterface[]
     */
    public function getSuggestions()
    {
        return $this->suggestions;
    }
}