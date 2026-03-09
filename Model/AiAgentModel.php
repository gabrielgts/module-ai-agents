<?php

namespace Gtstudio\AiAgents\Model;

use Gtstudio\AiAgents\Model\ResourceModel\AiAgentResource;
use Magento\Framework\Model\AbstractModel;

class AiAgentModel extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'gtstudio_ai_agent_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(AiAgentResource::class);
    }
}
