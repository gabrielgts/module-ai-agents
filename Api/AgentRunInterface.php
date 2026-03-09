<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Api;

use Gtstudio\AiConnector\Model\Exception\AiConnectorException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Execute a named AI agent with a single user message and return its reply.
 *
 * @api
 */
interface AgentRunInterface
{
    /**
     * Run an agent identified by its code and return the assistant's reply
     * along with token usage metadata.
     *
     * @param string $code    Machine-readable agent code (e.g. "customer_support").
     * @param string $message The user message to send to the agent.
     * @return array{
     *     content: string,
     *     tokens: int,
     *     input_tokens: int,
     *     output_tokens: int,
     *     model: string,
     *     provider: string
     * }
     *
     * @throws NoSuchEntityException When no agent with the given code exists.
     * @throws AiConnectorException  When the AI provider cannot be resolved or the request fails.
     */
    public function run(string $code, string $message): array;
}
