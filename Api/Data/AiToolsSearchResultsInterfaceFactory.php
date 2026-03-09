<?php

namespace Gtstudio\AiAgents\Api\Data;

/**
 * Factory interface for creating AiToolsSearchResults instances.
 */
interface AiToolsSearchResultsInterfaceFactory
{
    /**
     * Create a new AiToolsSearchResults instance.
     *
     * @param array $data
     * @return AiToolsSearchResultsInterface
     */
    public function create(array $data = []): AiToolsSearchResultsInterface;
}
