<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class AgentExecutionLogResource extends AbstractDb
{
    protected $_eventPrefix = 'gtstudio_ai_agent_execution_log_resource_model';

    protected function _construct(): void
    {
        $this->_init('gtstudio_ai_agent_execution_log', 'entity_id');
        $this->_useIsObjectNew = true;
    }
}
