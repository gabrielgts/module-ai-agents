<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Controller\Adminhtml\AiAgent;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;

class Execution extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Gtstudio_AiAgents::agents';

    public function __construct(
        Context $context,
        private readonly PageFactory $pageFactory
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $page = $this->pageFactory->create();
        $page->getConfig()->getTitle()->prepend(__('Agent Execution Log'));

        return $page;
    }
}
