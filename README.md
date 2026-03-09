# Gtstudio_AiAgents

Agent and tool registry for Magento 2. Provides a full admin UI to define, store, and execute AI agents backed by `Gtstudio_AiConnector`.

## What It Does

- Admin CRUD for **Agents** — each agent has a system prompt (background, steps, output instructions), a list of tools it can call, and optional cron scheduling
- Admin CRUD for **Tools** — reusable tool definitions that the NeuronAI framework exposes to agents
- **Run Immediately** — execute any agent from its edit page with a custom input and see the result in a modal
- **Cron Scheduling** — enable a cron expression per agent so it runs automatically on a schedule
- **Execution Log** — every run is recorded with status, input, output, token counts, and trigger source
- **Auto-pruning** — execution log entries are deleted after a configurable number of days

## Requirements

- Magento 2.4.4+
- PHP 8.1+
- `Gtstudio_AiConnector` enabled and configured

## Installation

```bash
php bin/magento module:enable Gtstudio_AiAgents
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento cache:flush
```

## Admin UI

*AI Studio → Agents & Tools*

### Agents

| Field | Description |
|-------|-------------|
| Code | Unique machine-readable identifier, e.g. `customer_support` |
| Description | Internal label — not sent to the LLM |
| Background | Agent identity (maps to NeuronAI `SystemPrompt::$background`) |
| Steps | Internal reasoning steps (`SystemPrompt::$steps`) |
| Output Instructions | Response format rules (`SystemPrompt::$output`) |
| Tools | Multi-select of registered tools |
| Additional Configs | Optional JSON for advanced NeuronAI overrides |

**Scheduling**

| Field | Description |
|-------|-------------|
| Enable Cron | Toggle to activate scheduled execution |
| Cron Expression | Standard 5-field expression, e.g. `0 * * * *` |
| Cron Input Template | Input sent to the agent on each scheduled run. Supports `{{date}}`, `{{datetime}}`, `{{timestamp}}`, `{{store_name}}`, `{{store_url}}` |

### Execution Log

*AI Studio → Agents & Tools → Execution Log*

Lists every agent run with status, token counts, trigger source (manual / cron), and error messages.

Log retention is controlled by:
*Stores → Configuration → aiagents → Execution → Log Retention Days* (default: 30)

## Running an Agent Programmatically

```php
use Gtstudio\AiAgents\Api\AgentRunInterface;

class MyService
{
    public function __construct(private readonly AgentRunInterface $agentRunner) {}

    public function run(): void
    {
        $result = $this->agentRunner->run('customer_support', 'How do I reset my password?');

        echo $result['content'];        // agent reply
        echo $result['tokens'];         // total tokens used
        echo $result['input_tokens'];   // input tokens
        echo $result['output_tokens'];  // output tokens
        echo $result['model'];          // model used
        echo $result['provider'];       // provider key
    }
}
```

## Extensibility

### Registering a Tool Executor

Tools define what the agent *can* call; executors contain the actual PHP logic that runs when an agent chooses a tool.

1. Create the tool record in *AI Studio → Agents & Tools → Tools* with a unique `code` (e.g. `get_order_status`).
2. Implement `Gtstudio\AiAgents\Api\ToolExecutorInterface`:

```php
namespace Vendor\Module\Model\Tool;

use Gtstudio\AiAgents\Api\ToolExecutorInterface;

class GetOrderStatusExecutor implements ToolExecutorInterface
{
    /**
     * @param array $parameters  Parameters chosen by the LLM from the tool's property definitions.
     * @return string|array      Result shown to the LLM (string) or returned as data (array).
     */
    public function execute(array $parameters): string|array
    {
        $orderId = $parameters['order_id'] ?? null;
        // ... fetch order, return status string
        return "Order #{$orderId} is currently: Shipped.";
    }
}
```

3. Register it in `etc/di.xml`:

```xml
<type name="Gtstudio\AiAgents\Model\Tool\ToolExecutorPool">
    <arguments>
        <argument name="executors" xsi:type="array">
            <item name="get_order_status" xsi:type="object">
                Vendor\Module\Model\Tool\GetOrderStatusExecutor
            </item>
        </argument>
    </arguments>
</type>
```

The key (`get_order_status`) must match the `code` field stored in the `gtstudio_ai_tools` table.

### Using AgentExecutionService Directly

For advanced use-cases (custom trigger sources, post-processing), inject `AgentExecutionService`:

```php
use Gtstudio\AiAgents\Model\AgentExecutionLogModel;
use Gtstudio\AiAgents\Model\Service\AgentExecutionService;

$log = $this->executionService->execute(
    'my_agent_code',
    'Input text here',
    AgentExecutionLogModel::TRIGGERED_MANUAL  // or TRIGGERED_CRON
);

if ($log->getData('status') === AgentExecutionLogModel::STATUS_SUCCESS) {
    $output = $log->getData('output');
}
```

### Cron Expression Syntax

The built-in matcher supports the five standard fields (minute, hour, day, month, weekday):

| Pattern | Meaning |
|---------|---------|
| `* * * * *` | Every minute |
| `0 * * * *` | Every hour |
| `0 0 * * *` | Daily at midnight |
| `*/15 * * * *` | Every 15 minutes |
| `0 9 * * 1-5` | Weekdays at 09:00 |
| `0 6,18 * * *` | 06:00 and 18:00 daily |

### Template Variables in Cron Input

| Token | Replaced With |
|-------|--------------|
| `{{date}}` | `2026-03-08` |
| `{{datetime}}` | `2026-03-08 14:00:00` |
| `{{timestamp}}` | Unix timestamp |
| `{{store_name}}` | Default store view name |
| `{{store_url}}` | Default store base URL |

Example: `Generate a daily sales summary for {{date}} on {{store_name}}.`

## Database Tables

| Table | Purpose |
|-------|---------|
| `gtstudio_ai_agent` | Agent definitions |
| `gtstudio_ai_tools` | Tool definitions |
| `gtstudio_ai_agent_execution_log` | Execution history |
