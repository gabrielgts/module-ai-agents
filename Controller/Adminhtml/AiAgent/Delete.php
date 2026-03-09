<?php

namespace Gtstudio\AiAgents\Controller\Adminhtml\AiAgent;

use Gtstudio\AiAgents\Api\Data\AiAgentInterface;
use Gtstudio\AiAgents\Api\DeleteAiAgentByIdInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Delete AiAgent controller.
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
     * @var DeleteAiAgentByIdInterface
     */
    private DeleteAiAgentByIdInterface $deleteByIdCommand;

    /**
     * @param Context $context
     * @param DeleteAiAgentByIdInterface $deleteByIdCommand
     */
    public function __construct(
        Context $context,
        DeleteAiAgentByIdInterface $deleteByIdCommand
    )
    {
        parent::__construct($context);
        $this->deleteByIdCommand = $deleteByIdCommand;
    }

    /**
     * Delete AiAgent action.
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var ResultInterface $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/*/');
        $entityId = (int)$this->getRequest()->getParam(AiAgentInterface::ENTITY_ID);

        try {
            $this->deleteByIdCommand->execute($entityId);
            $this->messageManager->addSuccessMessage(__('You have successfully deleted AiAgent entity'));
        } catch (CouldNotDeleteException|NoSuchEntityException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        return $resultRedirect;
    }
}
