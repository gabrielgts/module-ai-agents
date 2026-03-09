<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Model\Mapper;

use Gtstudio\AiAgents\Api\Data\AiAgentInterface;
use Gtstudio\AiAgents\Api\Data\AiToolsInterface;
use Gtstudio\AiAgents\Api\Data\AiToolsInterfaceFactory;
use Gtstudio\AiAgents\Api\GetAiToolsListInterface;
use Gtstudio\AiAgents\Model\Agent\MagentoAgent;
use Gtstudio\AiAgents\Model\Agent\MagentoAgentFactory;
use Gtstudio\AiConnector\Model\Client\NeuronClient;
use Gtstudio\AiConnector\Model\Exception\AiConnectorException;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Serialize\Serializer\Json;
use NeuronAI\SystemPrompt;

/**
 * Maps an AiAgentInterface entity to a fully-configured MagentoAgent.
 *
 * Entity fields are applied to the Neuron AI SystemPrompt as follows:
 *   - `background`         → Identity and purpose (one directive per line)
 *   - `steps`              → Internal reasoning steps (one step per line)
 *   - `output`             → Output format rules (one rule per line)
 *   - `additional_configs` → JSON object; `tools_usage` array fed into SystemPrompt::$toolsUsage
 *
 * The `tools` field (comma-separated entity IDs) is resolved to actual
 * AiTools entities, which are mapped to NeuronAI Tool instances via ToolMapper
 * and attached to the agent. Tool entities are cached by their sorted ID set
 * to avoid redundant DB queries across repeated agent invocations.
 *
 * The AI provider is supplied by NeuronClient (Gtstudio_AiConnector), ensuring
 * a single source of truth for LLM configuration across all agents.
 */
class AgentMapper
{
    /** Cache tag used when saving tool entities. */
    private const CACHE_TAG = 'gtstudio_ai_tools';

    /** TTL in seconds for cached tool entity data (1 hour). */
    private const CACHE_TTL = 3600;

    /**
     * @param ToolMapper $toolMapper
     * @param GetAiToolsListInterface $getAiToolsList
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param NeuronClient $neuronClient
     * @param MagentoAgentFactory $magentoAgentFactory
     * @param CacheInterface $cache
     * @param Json $serializer
     * @param AiToolsInterfaceFactory $toolsFactory
     */
    public function __construct(
        private readonly ToolMapper $toolMapper,
        private readonly GetAiToolsListInterface $getAiToolsList,
        private readonly SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        private readonly NeuronClient $neuronClient,
        private readonly MagentoAgentFactory $magentoAgentFactory,
        private readonly CacheInterface $cache,
        private readonly Json $serializer,
        private readonly AiToolsInterfaceFactory $toolsFactory
    ) {
    }

    /**
     * Build a fully-configured MagentoAgent from an AiAgent entity.
     *
     * @param AiAgentInterface $agentEntity The Magento agent entity to convert.
     * @return MagentoAgent A Neuron AI agent ready to receive chat messages.
     *
     * @throws AiConnectorException When the AI provider cannot be resolved
     *                              (e.g. module disabled or missing configuration).
     */
    public function map(AiAgentInterface $agentEntity): MagentoAgent
    {
        $agent = $this->magentoAgentFactory->create();

        $agent->setAiProvider($this->neuronClient->resolveProvider());
        $agent->setInstructions($this->buildSystemPrompt($agentEntity));

        foreach ($this->resolveTools($agentEntity) as $tool) {
            $agent->addTool($tool);
        }

        return $agent;
    }

    /**
     * Compose a NeuronAI SystemPrompt from the agent entity's text fields.
     *
     * Each field is treated as a newline-separated list of items. Empty lines
     * and surrounding whitespace are discarded automatically.
     *
     * The `additional_configs` JSON field may contain a `tools_usage` array,
     * which is passed to SystemPrompt::$toolsUsage to define tool-calling rules.
     *
     * @param AiAgentInterface $entity
     * @return string Rendered system prompt string.
     */
    private function buildSystemPrompt(AiAgentInterface $entity): string
    {
        $configs = $this->parseAdditionalConfigs($entity->getAdditionalConfigs());

        return (string) new SystemPrompt(
            background: $this->parseLines($entity->getBackground()),
            steps: $this->parseLines($entity->getSteps()),
            output: $this->parseLines($entity->getOutput()),
            toolsUsage: $configs['tools_usage'] ?? [],
        );
    }

    /**
     * Decode the optional additional_configs JSON field.
     *
     * Returns an empty array for null, empty, or invalid JSON values.
     *
     * @param string|null $json Raw JSON string from the admin form.
     * @return array<string, mixed>
     */
    private function parseAdditionalConfigs(?string $json): array
    {
        if (empty($json)) {
            return [];
        }

        try {
            return (array) $this->serializer->unserialize($json);
        } catch (\InvalidArgumentException) {
            return [];
        }
    }

    /**
     * Parse a newline-delimited string into a list of non-empty trimmed lines.
     *
     * @param string|null $text Raw multiline text from the admin form.
     * @return string[]
     */
    private function parseLines(?string $text): array
    {
        if (empty(trim($text ?? ''))) {
            return [];
        }

        return array_values(
            array_filter(
                array_map('trim', explode("\n", $text))
            )
        );
    }

    /**
     * Fetch the AiTools entities referenced by the agent and map them to NeuronAI Tools.
     *
     * Tool entity data is cached using a key derived from the sorted list of IDs,
     * ensuring a unique cache entry per distinct tool set and preventing stale reads
     * when the set changes.
     *
     * @param AiAgentInterface $entity
     * @return \NeuronAI\Tools\Tool[]
     */
    private function resolveTools(AiAgentInterface $entity): array
    {
        $toolIds = array_filter(
            array_map('intval', explode(',', $entity->getTools() ?? ''))
        );

        if (empty($toolIds)) {
            return [];
        }

        sort($toolIds);
        $cacheKey = 'gtstudio_ai_tools_' . hash('sha256', implode(',', $toolIds));

        $cached = $this->cache->load($cacheKey);

        if ($cached !== false) {
            $toolsData = $this->serializer->unserialize($cached);
            $items = array_map(function (array $data) {
                $tool = $this->toolsFactory->create();
                $tool->addData($data);
                return $tool;
            }, $toolsData);

            return array_map([$this->toolMapper, 'map'], $items);
        }

        $builder = $this->searchCriteriaBuilderFactory->create();
        $builder->addFilter('entity_id', $toolIds, 'in');
        $result = $this->getAiToolsList->execute($builder->create());
        $items = $result->getItems();

        $this->cache->save(
            $this->serializer->serialize(array_map(fn($tool) => [
                AiToolsInterface::ENTITY_ID        => $tool->getEntityId(),
                AiToolsInterface::CODE             => $tool->getCode(),
                AiToolsInterface::DESCRIPTION      => $tool->getDescription(),
                AiToolsInterface::PROPERTIES       => $tool->getProperties(),
                AiToolsInterface::ADDITIONAL_CONFIGS => $tool->getAdditionalConfigs(),
            ], $items)),
            $cacheKey,
            [self::CACHE_TAG],
            self::CACHE_TTL
        );

        return array_map([$this->toolMapper, 'map'], $items);
    }
}
