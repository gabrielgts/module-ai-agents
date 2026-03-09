<?php

namespace Gtstudio\AiAgents\Api;

use Gtstudio\AiAgents\Api\Data\AiToolsSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Get AiTools list by search criteria query.
 *
 * @api
 */
interface GetAiToolsListInterface
{
    /**
     * Get AiTools list by search criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface|null $searchCriteria
     * @return \Gtstudio\AiAgents\Api\Data\AiToolsSearchResultsInterface
     */
    public function execute(?SearchCriteriaInterface $searchCriteria = null): AiToolsSearchResultsInterface;
}
