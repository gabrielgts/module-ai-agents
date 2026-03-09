<?php

namespace Gtstudio\AiAgents\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * AiAgent entity search result.
 */
interface AiAgentSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Set items.
     *
     * @param \Gtstudio\AiAgents\Api\Data\AiAgentInterface[] $items
     *
     * @return AiAgentSearchResultsInterface
     */
    public function setItems(array $items): AiAgentSearchResultsInterface;

    /**
     * Get items.
     *
     * @return \Gtstudio\AiAgents\Api\Data\AiAgentInterface[]
     */
    public function getItems(): array;
}
