<?php

namespace Gtstudio\AiAgents\Controller\Adminhtml\AiAgent;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * AiAgent backend index (list) controller.
 */
class Index extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session.
     */
    public const ADMIN_RESOURCE = 'Gtstudio_AiAgents::management';

    /**
     * Execute action based on request and return result.
     *
     * @return ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->setActiveMenu('Gtstudio_AiAgents::management');
        $resultPage->addBreadcrumb(__('AiAgent'), __('AiAgent'));
        $resultPage->addBreadcrumb(__('Manage AiAgents'), __('Manage AiAgents'));
        $resultPage->getConfig()->getTitle()->prepend(__('AiAgent List'));

        return $resultPage;
    }
}
