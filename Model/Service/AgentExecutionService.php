<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Model\Service;

use Gtstudio\AiAgents\Api\AgentRunInterface;
use Gtstudio\AiAgents\Api\GetAiAgentByCodeInterface;
use Gtstudio\AiAgents\Model\AgentExecutionLogModel;
use Gtstudio\AiAgents\Model\AgentExecutionLogModelFactory;
use Gtstudio\AiAgents\Model\ResourceModel\AgentExecutionLogResource;
use Psr\Log\LoggerInterface;

/**
 * Orchestrates agent execution and persists a structured log entry.
 */
class AgentExecutionService
{
    public function __construct(
        private readonly AgentRunInterface $agentRunner,
        private readonly GetAiAgentByCodeInterface $getAiAgentByCode,
        private readonly AgentExecutionLogModelFactory $logFactory,
        private readonly AgentExecutionLogResource $logResource,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Execute an agent by code, log the result and return the log model.
     *
     * @param string $agentCode
     * @param string $input
     * @param string $triggeredBy  'manual' or 'cron'
     * @return AgentExecutionLogModel
     */
    public function execute(
        string $agentCode,
        string $input,
        string $triggeredBy = AgentExecutionLogModel::TRIGGERED_MANUAL
    ): AgentExecutionLogModel {
        /** @var AgentExecutionLogModel $log */
        $log = $this->logFactory->create();

        try {
            $agentEntity = $this->getAiAgentByCode->execute($agentCode);
            $agentEntityId = $agentEntity->getEntityId();
        } catch (\Exception) {
            $agentEntityId = null;
        }

        $log->setData([
            'agent_code'      => $agentCode,
            'agent_entity_id' => $agentEntityId,
            'input'           => $input,
            'status'          => AgentExecutionLogModel::STATUS_RUNNING,
            'triggered_by'    => $triggeredBy,
            'tokens_input'    => 0,
            'tokens_output'   => 0,
        ]);

        try {
            $this->logResource->save($log);
        } catch (\Exception $e) {
            $this->logger->error('AiAgents: failed to create execution log', ['exception' => $e]);
        }

        try {
            $result = $this->agentRunner->run($agentCode, $input);

            $log->setData('output', $result['content'] ?? '');
            $log->setData('status', AgentExecutionLogModel::STATUS_SUCCESS);
            $log->setData('tokens_input', $result['input_tokens'] ?? 0);
            $log->setData('tokens_output', $result['output_tokens'] ?? 0);
        } catch (\Exception $e) {
            $this->logger->error('AiAgents: agent execution failed', [
                'agent_code' => $agentCode,
                'exception'  => $e,
            ]);

            $log->setData('status', AgentExecutionLogModel::STATUS_ERROR);
            $log->setData('error_message', $e->getMessage());
        }

        try {
            $this->logResource->save($log);
        } catch (\Exception $e) {
            $this->logger->error('AiAgents: failed to update execution log', ['exception' => $e]);
        }

        return $log;
    }

    /**
     * Delete execution log entries older than $days days.
     *
     * @return int  Number of rows deleted.
     */
    public function pruneOldLogs(int $days): int
    {
        $connection = $this->logResource->getConnection();
        $table      = $this->logResource->getMainTable();

        $cutoff = (new \DateTime())->modify("-{$days} days")->format('Y-m-d H:i:s');

        return (int) $connection->delete($table, ['created_at < ?' => $cutoff]);
    }
}
