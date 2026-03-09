<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Model\Tool;

use Gtstudio\AiAgents\Api\ToolExecutorInterface;

/**
 * Registry of ToolExecutorInterface implementations keyed by tool code.
 *
 * Populate via di.xml:
 *
 *   <type name="Gtstudio\AiAgents\Model\Tool\ToolExecutorPool">
 *       <arguments>
 *           <argument name="executors" xsi:type="array">
 *               <item name="my_tool_code" xsi:type="object">Vendor\Module\Model\Tool\MyToolExecutor</item>
 *           </argument>
 *       </arguments>
 *   </type>
 */
class ToolExecutorPool
{
    /**
     * @param array<string, ToolExecutorInterface> $executors Executors keyed by tool code.
     */
    public function __construct(
        private readonly array $executors = []
    ) {
    }

    /**
     * Retrieve the executor registered for the given tool code.
     *
     * Returns null when no executor has been registered, which is valid for
     * definition-only tools that are not yet implemented in PHP.
     *
     * @param string $toolCode The tool `code` value stored in the database.
     * @return ToolExecutorInterface|null
     */
    public function get(string $toolCode): ?ToolExecutorInterface
    {
        return $this->executors[$toolCode] ?? null;
    }

    /**
     * Check whether an executor is registered for the given tool code.
     *
     * @param string $toolCode The tool `code` value stored in the database.
     */
    public function has(string $toolCode): bool
    {
        return isset($this->executors[$toolCode]);
    }
}
