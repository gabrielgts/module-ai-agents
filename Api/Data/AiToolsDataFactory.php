<?php

namespace Gtstudio\AiAgents\Api\Data;

/**
 * Factory interface for creating AiToolsData instances.
 */
interface AiToolsDataFactory
{
    /**
     * Create a new AiToolsData instance.
     *
     * @param array $data
     * @return AiToolsInterface
     */
    public function create(array $data = []): AiToolsInterface;
}
