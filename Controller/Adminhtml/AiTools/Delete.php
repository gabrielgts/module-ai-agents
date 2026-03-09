<?php

namespace Gtstudio\AiAgents\Controller\Adminhtml\AiTools;

use Gtstudio\AiAgents\Api\Data\AiToolsInterface;
use Gtstudio\AiAgents\Api\DeleteAiToolsByIdInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Delete AiTools controller.
 */
class Delete extends Action implements HttpPostActionInterface, HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session.
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Gtstudio_AiAgents::management';

    /**
     * @var DeleteAiToolsByIdInterface
     */
    private DeleteAiToolsByIdInterface $deleteByIdCommand;

    /**
     * @param Context $context
     * @param DeleteAiToolsByIdInterface $deleteByIdCommand
     */
    public function __construct(
        Context $context,
        DeleteAiToolsByIdInterface $deleteByIdCommand
    )
    {
        parent::__construct($context);
        $this->deleteByIdCommand = $deleteByIdCommand;
    }

    /**
     * Delete AiTools action.
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var ResultInterface $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/*/');
        $entityId = (int)$this->getRequest()->getParam(AiToolsInterface::ENTITY_ID);

        try {
            $this->deleteByIdCommand->execute($entityId);
            $this->messageManager->addSuccessMessage(__('You have successfully deleted AiTools entity'));
        } catch (CouldNotDeleteException|NoSuchEntityException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        return $resultRedirect;
    }
}
