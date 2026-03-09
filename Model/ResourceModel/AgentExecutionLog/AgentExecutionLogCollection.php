<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Model\ResourceModel\AgentExecutionLog;

use Gtstudio\AiAgents\Model\AgentExecutionLogModel;
use Gtstudio\AiAgents\Model\ResourceModel\AgentExecutionLogResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class AgentExecutionLogCollection extends AbstractCollection
{
    protected $_eventPrefix = 'gtstudio_ai_agent_execution_log_collection';

    protected function _construct(): void
    {
        $this->_init(AgentExecutionLogModel::class, AgentExecutionLogResource::class);
    }
}
