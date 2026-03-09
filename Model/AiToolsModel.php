<?php

namespace Gtstudio\AiAgents\Model;

use Gtstudio\AiAgents\Model\ResourceModel\AiToolsResource;
use Magento\Framework\Model\AbstractModel;

class AiToolsModel extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'gtstudio_ai_tools_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(AiToolsResource::class);
    }
}
