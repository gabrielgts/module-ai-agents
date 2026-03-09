<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Model;

use Gtstudio\AiAgents\Api\AgentRunInterface;
use Gtstudio\AiAgents\Api\GetAiAgentByCodeInterface;
use Gtstudio\AiAgents\Model\Mapper\AgentMapper;
use Gtstudio\AiConnector\Model\Config\ConfigProvider;
use NeuronAI\Chat\Messages\UserMessage;

/**
 * Orchestrates the full lifecycle of a single agent request:
 * load entity → map to NeuronAI agent → execute chat → return reply + usage.
 */
class AgentRunner implements AgentRunInterface
{
    public function __construct(
        private readonly GetAiAgentByCodeInterface $getAiAgentByCode,
        private readonly AgentMapper $agentMapper,
        private readonly ConfigProvider $configProvider
    ) {
    }

    /**
     * @inheritDoc
     */
    public function run(string $code, string $message): array
    {
        $agentEntity = $this->getAiAgentByCode->execute($code);
        $agent = $this->agentMapper->map($agentEntity);

        $response = $agent->chat(new UserMessage($message));

        $usage = $response->getUsage();
        $inputTokens  = $usage?->inputTokens  ?? 0;
        $outputTokens = $usage?->outputTokens ?? 0;

        return [
            'content'       => (string) $response->getContent(),
            'tokens'        => $inputTokens + $outputTokens,
            'input_tokens'  => $inputTokens,
            'output_tokens' => $outputTokens,
            'model'         => $this->configProvider->getModel(),
            'provider'      => $this->configProvider->getProvider(),
        ];
    }
}
