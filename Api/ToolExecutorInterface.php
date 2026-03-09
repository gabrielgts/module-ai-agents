<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Api;

/**
 * Contract for implementations that back a database-configured AI tool.
 *
 * Register concrete executors in di.xml under ToolExecutorPool's
 * $executors argument, keyed by the tool's `code` value.
 */
interface ToolExecutorInterface
{
    /**
     * Execute the tool logic and return a result.
     *
     * The $inputs array is keyed by the property names declared on the tool
     * entity, matching exactly what the LLM resolved for each parameter.
     *
     * @param array<string, mixed> $inputs Named inputs provided by the LLM.
     * @return mixed The tool result, which will be serialised and sent back to the LLM.
     */
    public function execute(array $inputs): mixed;
}
