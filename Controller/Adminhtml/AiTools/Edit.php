<?php

namespace Gtstudio\AiAgents\Controller\Adminhtml\AiTools;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Edit AiTools entity backend controller.
 */
class Edit extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session.
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Gtstudio_AiAgents::management';

    /**
     * Edit AiTools action.
     *
     * @return Page|ResultInterface
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Gtstudio_AiAgents::management');
        $resultPage->getConfig()->getTitle()->prepend(__('Edit AiTools'));

        return $resultPage;
    }
}
