<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Model;

use Magento\Framework\Model\AbstractModel;
use Gtstudio\AiAgents\Model\ResourceModel\AgentExecutionLogResource;

class AgentExecutionLogModel extends AbstractModel
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_RUNNING = 'running';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_ERROR   = 'error';

    public const TRIGGERED_MANUAL = 'manual';
    public const TRIGGERED_CRON   = 'cron';

    protected $_eventPrefix = 'gtstudio_ai_agent_execution_log';

    protected function _construct(): void
    {
        $this->_init(AgentExecutionLogResource::class);
    }
}
