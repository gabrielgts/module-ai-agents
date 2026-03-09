<?php

namespace Gtstudio\AiAgents\Api\Data;

/**
 * Factory interface for creating AiAgentData instances.
 */
interface AiAgentDataFactory
{
    /**
     * Create a new AiAgentData instance.
     *
     * @param array $data
     * @return AiAgentInterface
     */
    public function create(array $data = []): AiAgentInterface;
}
