<?php

namespace Gtstudio\AiAgents\Api;

use Gtstudio\AiAgents\Api\Data\AiAgentSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Get AiAgent list by search criteria query.
 *
 * @api
 */
interface GetAiAgentListInterface
{
    /**
     * Get AiAgent list by search criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface|null $searchCriteria
     * @return \Gtstudio\AiAgents\Api\Data\AiAgentSearchResultsInterface
     */
    public function execute(?SearchCriteriaInterface $searchCriteria = null): AiAgentSearchResultsInterface;
}
