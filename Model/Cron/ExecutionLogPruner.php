<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Model\Cron;

use Gtstudio\AiAgents\Model\Service\AgentExecutionService;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Psr\Log\LoggerInterface;

/**
 * Daily cron job that deletes execution log entries older than the configured
 * retention period (aiagents/execution/log_retention_days, default 30).
 */
class ExecutionLogPruner
{
    private const CONFIG_PATH = 'aiagents/execution/log_retention_days';
    private const DEFAULT_DAYS = 30;

    public function __construct(
        private readonly AgentExecutionService $executionService,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly LoggerInterface $logger
    ) {
    }

    public function execute(): void
    {
        $days = (int) ($this->scopeConfig->getValue(self::CONFIG_PATH) ?: self::DEFAULT_DAYS);

        if ($days <= 0) {
            return;
        }

        $deleted = $this->executionService->pruneOldLogs($days);

        $this->logger->info("AiAgents: pruned {$deleted} execution log entries older than {$days} days.");
    }
}
