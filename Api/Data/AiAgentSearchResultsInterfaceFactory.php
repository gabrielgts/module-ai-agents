<?php

namespace Gtstudio\AiAgents\Api\Data;

/**
 * Factory interface for creating AiAgentSearchResults instances.
 */
// phpcs:ignore Magento2.NamingConvention.InterfaceName.WrongInterfaceName
interface AiAgentSearchResultsInterfaceFactory
{
    /**
     * Create a new AiAgentSearchResults instance.
     *
     * @param array $data
     * @return AiAgentSearchResultsInterface
     */
    public function create(array $data = []): AiAgentSearchResultsInterface;
}
