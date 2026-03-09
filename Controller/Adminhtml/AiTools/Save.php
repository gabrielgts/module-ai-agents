<?php

namespace Gtstudio\AiAgents\Controller\Adminhtml\AiTools;

use Gtstudio\AiAgents\Api\Data\AiToolsInterface;
use Gtstudio\AiAgents\Api\Data\AiToolsInterfaceFactory;
use Gtstudio\AiAgents\Api\SaveAiToolsInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Save AiTools controller action.
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
     * @var SaveAiToolsInterface
     */
    private SaveAiToolsInterface $saveCommand;

    /**
     * @var AiToolsInterfaceFactory
     */
    private AiToolsInterfaceFactory $entityDataFactory;

    /**
     * @var Json
     */
    private Json $json;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param SaveAiToolsInterface $saveCommand
     * @param AiToolsInterfaceFactory $entityDataFactory
     * @param Json $json
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        SaveAiToolsInterface $saveCommand,
        AiToolsInterfaceFactory $entityDataFactory,
        Json $json
    )
    {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
        $this->saveCommand = $saveCommand;
        $this->entityDataFactory = $entityDataFactory;
        $this->json = $json;
    }

    /**
     * Save AiTools Action.
     *
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $params = $this->getRequest()->getParams();

        try {
            $generalData = $params['general'];
            if (isset($generalData[AiToolsInterface::PROPERTIES]) && is_array($generalData[AiToolsInterface::PROPERTIES])) {
                $generalData[AiToolsInterface::PROPERTIES] = $this->json->serialize(
                    array_values($generalData[AiToolsInterface::PROPERTIES])
                );
            }

            /** @var AiToolsInterface|DataObject $entityModel */
            $entityModel = $this->entityDataFactory->create();
            $entityModel->addData($generalData);
            $this->saveCommand->execute($entityModel);
            $this->messageManager->addSuccessMessage(
                __('The AiTools data was saved successfully')
            );
            $this->dataPersistor->clear('entity');
        } catch (CouldNotSaveException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
            $this->dataPersistor->set('entity', $params);

            return $resultRedirect->setPath('*/*/edit', [
                AiToolsInterface::ENTITY_ID => $this->getRequest()->getParam(AiToolsInterface::ENTITY_ID)
            ]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
