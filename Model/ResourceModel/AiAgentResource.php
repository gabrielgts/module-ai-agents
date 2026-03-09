<?php

namespace Gtstudio\AiAgents\Model\ResourceModel;

use Gtstudio\AiAgents\Api\Data\AiAgentInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class AiAgentResource extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'gtstudio_ai_agent_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('gtstudio_ai_agent', AiAgentInterface::ENTITY_ID);
        $this->_useIsObjectNew = true;
    }
}
