<?php

namespace Gtstudio\AiAgents\Model;

use Gtstudio\AiAgents\Api\Data\AiAgentSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

/**
 * AiAgent entity search results implementation.
 */
class AiAgentSearchResults extends SearchResults implements AiAgentSearchResultsInterface
{
    /**
     * Set items list.
     *
     * @param array $items
     *
     * @return AiAgentSearchResultsInterface
     */
    public function setItems(array $items): AiAgentSearchResultsInterface
    {
        return parent::setItems($items);
    }

    /**
     * Get items list.
     *
     * @return array
     */
    public function getItems(): array
    {
        return parent::getItems();
    }
}
