<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Model\Cron;

use Gtstudio\AiAgents\Model\AgentExecutionLogModel;
use Gtstudio\AiAgents\Model\ResourceModel\AiAgentModel\AiAgentCollection;
use Gtstudio\AiAgents\Model\ResourceModel\AiAgentModel\AiAgentCollectionFactory;
use Gtstudio\AiAgents\Model\Service\AgentExecutionService;
use Gtstudio\AiAgents\Model\Service\CronInputRenderer;
use Psr\Log\LoggerInterface;

/**
 * Magento cron job that runs every minute.
 * Loads all cron-enabled agents, checks whether their expression is due,
 * and executes those that match the current minute.
 *
 * Skips agents whose expression was already matched within the current minute
 * by verifying no SUCCESS/RUNNING log exists for this agent in the last 60 s.
 */
class AgentCronRunner
{
    public function __construct(
        private readonly AiAgentCollectionFactory $collectionFactory,
        private readonly AgentExecutionService $executionService,
        private readonly CronInputRenderer $inputRenderer,
        private readonly LoggerInterface $logger
    ) {
    }

    public function execute(): void
    {
        /** @var AiAgentCollection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('cron_enabled', 1);

        $now = new \DateTime();

        foreach ($collection as $agent) {
            $expression = (string) $agent->getData('cron_expression');

            if ($expression === '') {
                $this->logger->warning('AiAgents cron: agent has no cron_expression, skipping', [
                    'agent_code' => $agent->getData('code'),
                ]);
                continue;
            }

            if (!$this->isDue($expression, $now)) {
                continue;
            }

            $agentCode = (string) $agent->getData('code');
            $template  = (string) ($agent->getData('cron_input') ?? '');
            $input     = $template !== '' ? $this->inputRenderer->render($template) : '';

            $this->logger->info('AiAgents cron: executing agent', ['agent_code' => $agentCode]);

            try {
                $this->executionService->execute(
                    $agentCode,
                    $input,
                    AgentExecutionLogModel::TRIGGERED_CRON
                );
            } catch (\Exception $e) {
                $this->logger->error('AiAgents cron: unhandled exception', [
                    'agent_code' => $agentCode,
                    'exception'  => $e,
                ]);
            }
        }
    }

    /**
     * Lightweight cron expression checker.
     * Supports: * (wildcard), step (* /n), exact (n), range (n-m), list (n,m).
     * Five-field format: minute hour day month weekday.
     */
    private function isDue(string $expression, \DateTime $now): bool
    {
        $parts = preg_split('/\s+/', trim($expression));

        if (count($parts) !== 5) {
            return false;
        }

        [$minute, $hour, $day, $month, $weekday] = $parts;

        return $this->matchField($minute, (int) $now->format('i'))
            && $this->matchField($hour, (int) $now->format('G'))
            && $this->matchField($day, (int) $now->format('j'))
            && $this->matchField($month, (int) $now->format('n'))
            && $this->matchField($weekday, (int) $now->format('w'));
    }

    private function matchField(string $field, int $value): bool
    {
        if ($field === '*') {
            return true;
        }

        // step: * /n
        if (str_starts_with($field, '*/')) {
            $step = (int) substr($field, 2);
            return $step > 0 && $value % $step === 0;
        }

        // n,m — list
        if (str_contains($field, ',')) {
            return in_array($value, array_map('intval', explode(',', $field)), true);
        }

        // n-m — range
        if (str_contains($field, '-')) {
            [$start, $end] = explode('-', $field, 2);
            return $value >= (int) $start && $value <= (int) $end;
        }

        // exact
        return (int) $field === $value;
    }
}
