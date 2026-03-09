<?php

namespace Gtstudio\AiAgents\Controller\Adminhtml\AiTools;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * AiTools backend index (list) controller.
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
        $resultPage->addBreadcrumb(__('AiTools'), __('AiTools'));
        $resultPage->addBreadcrumb(__('Manage AiToolss'), __('Manage AiToolss'));
        $resultPage->getConfig()->getTitle()->prepend(__('AiTools List'));

        return $resultPage;
    }
}
