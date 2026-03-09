<?php

namespace Gtstudio\AiAgents\Model\ResourceModel\AiAgentModel;

use Gtstudio\AiAgents\Model\AiAgentModel;
use Gtstudio\AiAgents\Model\ResourceModel\AiAgentResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class AiAgentCollection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'gtstudio_ai_agent_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(AiAgentModel::class, AiAgentResource::class);
    }
}
