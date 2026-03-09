<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Api;

use Gtstudio\AiAgents\Api\Data\AiAgentInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Retrieve a single AiAgent entity by its machine-readable code.
 *
 * @api
 */
interface GetAiAgentByCodeInterface
{
    /**
     * Return the agent entity matching the given code.
     *
     * @param string $code Unique code identifying the agent (e.g. "customer_support").
     * @return AiAgentInterface
     * @throws NoSuchEntityException When no agent with the given code exists.
     */
    public function execute(string $code): AiAgentInterface;
}
