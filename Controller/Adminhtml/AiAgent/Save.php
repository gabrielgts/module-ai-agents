<?php

namespace Gtstudio\AiAgents\Controller\Adminhtml\AiAgent;

use Gtstudio\AiAgents\Api\Data\AiAgentInterface;
use Gtstudio\AiAgents\Api\Data\AiAgentInterfaceFactory;
use Gtstudio\AiAgents\Api\SaveAiAgentInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Save AiAgent controller action.
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Gtstudio_AiAgents::management';

    /**
     * @var DataPersistorInterface
     */
    private DataPersistorInterface $dataPersistor;

    /**
     * @var SaveAiAgentInterface
     */
    private SaveAiAgentInterface $saveCommand;

    /**
     * @var AiAgentInterfaceFactory
     */
    private AiAgentInterfaceFactory $entityDataFactory;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param SaveAiAgentInterface $saveCommand
     * @param AiAgentInterfaceFactory $entityDataFactory
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        SaveAiAgentInterface $saveCommand,
        AiAgentInterfaceFactory $entityDataFactory
    )
    {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
        $this->saveCommand = $saveCommand;
        $this->entityDataFactory = $entityDataFactory;
    }

    /**
     * Save AiAgent Action.
     *
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $params = $this->getRequest()->getParams();

        try {
            $generalData = $params['general'] ?? [];

            if (isset($generalData[AiAgentInterface::TOOLS]) && is_array($generalData[AiAgentInterface::TOOLS])) {
                $generalData[AiAgentInterface::TOOLS] = implode(',', $generalData[AiAgentInterface::TOOLS]);
            }

            // Normalise toggle: unchecked checkboxes are not submitted; treat missing as 0.
            $generalData[AiAgentInterface::CRON_ENABLED] = isset($generalData[AiAgentInterface::CRON_ENABLED])
                ? (int) $generalData[AiAgentInterface::CRON_ENABLED]
                : 0;

            /** @var AiAgentInterface|DataObject $entityModel */
            $entityModel = $this->entityDataFactory->create();
            $entityModel->addData($generalData);
            $this->saveCommand->execute($entityModel);
            $this->messageManager->addSuccessMessage(
                __('The AiAgent data was saved successfully')
            );
            $this->dataPersistor->clear('entity');
        } catch (CouldNotSaveException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
            $this->dataPersistor->set('entity', $params);

            return $resultRedirect->setPath('*/*/edit', [
                AiAgentInterface::ENTITY_ID => $this->getRequest()->getParam(AiAgentInterface::ENTITY_ID)
            ]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
