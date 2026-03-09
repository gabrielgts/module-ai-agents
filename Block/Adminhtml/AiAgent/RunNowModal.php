<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Block\Adminhtml\AiAgent;

use Gtstudio\AiAgents\Api\Data\AiAgentInterface;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;

class RunNowModal extends Template
{
    protected $_template = 'Gtstudio_AiAgents::aiagent/run_now_modal.phtml';

    public function __construct(Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }

    /**
     * @return int
     */
    public function getEntityId(): int
    {
        return (int) $this->getRequest()->getParam(AiAgentInterface::ENTITY_ID);
    }

    /**
     * @return string
     */
    public function getRunNowUrl(): string
    {
        return $this->getUrl('ai_agent/aiagent/runNow');
    }
}
