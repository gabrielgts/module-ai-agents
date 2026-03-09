<?php

namespace Gtstudio\AiAgents\Model;

use Gtstudio\AiAgents\Api\Data\AiToolsSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

/**
 * AiTools entity search results implementation.
 */
class AiToolsSearchResults extends SearchResults implements AiToolsSearchResultsInterface
{
    /**
     * Set items list.
     *
     * @param array $items
     *
     * @return AiToolsSearchResultsInterface
     */
    public function setItems(array $items): AiToolsSearchResultsInterface
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
