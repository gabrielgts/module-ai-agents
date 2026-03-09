<?php

namespace Gtstudio\AiAgents\Model\ResourceModel\AiToolsModel;

use Gtstudio\AiAgents\Model\AiToolsModel;
use Gtstudio\AiAgents\Model\ResourceModel\AiToolsResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class AiToolsCollection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'gtstudio_ai_tools_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(AiToolsModel::class, AiToolsResource::class);
    }
}
