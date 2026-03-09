<?php

namespace Gtstudio\AiAgents\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * AiTools entity search result.
 */
interface AiToolsSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Set items.
     *
     * @param \Gtstudio\AiAgents\Api\Data\AiToolsInterface[] $items
     *
     * @return AiToolsSearchResultsInterface
     */
    public function setItems(array $items): AiToolsSearchResultsInterface;

    /**
     * Get items.
     *
     * @return \Gtstudio\AiAgents\Api\Data\AiToolsInterface[]
     */
    public function getItems(): array;
}
