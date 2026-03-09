<?php

namespace Gtstudio\AiAgents\Model\ResourceModel;

use Gtstudio\AiAgents\Api\Data\AiToolsInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class AiToolsResource extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'gtstudio_ai_tools_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('gtstudio_ai_tools', AiToolsInterface::ENTITY_ID);
        $this->_useIsObjectNew = true;
    }
}
