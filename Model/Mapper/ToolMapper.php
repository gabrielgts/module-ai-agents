<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Model\Mapper;

use Gtstudio\AiAgents\Api\Data\AiToolsInterface;
use Gtstudio\AiAgents\Model\Tool\ToolExecutorPool;
use NeuronAI\Tools\Tool;

/**
 * Maps an AiToolsInterface entity to a NeuronAI Tool instance.
 *
 * The entity fields are mapped as follows:
 *   - `code`        → Tool name (used by the LLM to call the tool)
 *   - `description` → Tool description (sent to the LLM as guidance)
 *   - `properties`  → ToolProperty list (input schema for the LLM)
 *
 * If a ToolExecutorInterface is registered in ToolExecutorPool for the tool's
 * code, it is attached as the callable. Otherwise the tool is created without a
 * callable; attempts to execute it will throw ToolCallableNotSet.
 */
class ToolMapper
{
    public function __construct(
        private readonly ToolPropertyMapper $propertyMapper,
        private readonly ToolExecutorPool $executorPool
    ) {
    }

    /**
     * Convert an AiTools entity into a configured NeuronAI Tool.
     *
     * @param AiToolsInterface $toolEntity The Magento tool entity to convert.
     * @return Tool A Neuron AI Tool ready to be attached to an agent.
     */
    public function map(AiToolsInterface $toolEntity): Tool
    {
        $properties = $this->propertyMapper->mapFromJson($toolEntity->getProperties());

        $tool = Tool::make(
            name: (string)$toolEntity->getCode(),
            description: $toolEntity->getDescription(),
            properties: $properties,
        );

        $executor = $this->executorPool->get((string)$toolEntity->getCode());

        if ($executor !== null) {
            // Spread variadic to capture named arguments from Tool::execute() back into
            // an associative array and forward them to the executor's interface.
            $tool->setCallable(function (mixed ...$args) use ($executor): mixed {
                return $executor->execute($args);
            });
        }

        return $tool;
    }
}
