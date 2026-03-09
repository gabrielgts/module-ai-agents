<?php

namespace Gtstudio\AiAgents\Api;

use Gtstudio\AiAgents\Api\Data\AiAgentInterface;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Save AiAgent Command.
 *
 * @api
 */
interface SaveAiAgentInterface
{
    /**
     * Save AiAgent.
     * @param AiAgentInterface $aiAgent
     * @return int
     * @throws CouldNotSaveException
     */
    public function execute(AiAgentInterface $aiAgent): int;
}
