<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Controller\Adminhtml\AiAgent;

use Gtstudio\AiAgents\Api\GetAiAgentByCodeInterface;
use Gtstudio\AiAgents\Model\AgentExecutionLogModel;
use Gtstudio\AiAgents\Model\ResourceModel\AiAgentModel\AiAgentCollectionFactory;
use Gtstudio\AiAgents\Model\Service\AgentExecutionService;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class RunNow extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Gtstudio_AiAgents::agents';

    public function __construct(
        Context $context,
        private readonly AgentExecutionService $executionService,
        private readonly AiAgentCollectionFactory $collectionFactory,
        private readonly JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $result   = $this->jsonFactory->create();
        $entityId = (int) $this->getRequest()->getParam('entity_id');
        $input    = trim((string) $this->getRequest()->getParam('input', ''));

        if (!$entityId) {
            return $result->setData(['success' => false, 'message' => (string) __('Agent ID is required.')]);
        }

        /** @var \Gtstudio\AiAgents\Model\ResourceModel\AiAgentModel\AiAgentCollection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('entity_id', $entityId);
        $agent = $collection->getFirstItem();

        if (!$agent->getId()) {
            return $result->setData(['success' => false, 'message' => (string) __('Agent not found.')]);
        }

        $agentCode = (string) $agent->getData('code');

        try {
            $log = $this->executionService->execute($agentCode, $input, AgentExecutionLogModel::TRIGGERED_MANUAL);

            if ($log->getData('status') === AgentExecutionLogModel::STATUS_SUCCESS) {
                return $result->setData([
                    'success'       => true,
                    'output'        => (string) $log->getData('output'),
                    'tokens_input'  => (int) $log->getData('tokens_input'),
                    'tokens_output' => (int) $log->getData('tokens_output'),
                    'log_id'        => (int) $log->getId(),
                ]);
            }

            return $result->setData([
                'success' => false,
                'message' => (string) ($log->getData('error_message') ?: __('Agent execution failed.')),
                'log_id'  => (int) $log->getId(),
            ]);
        } catch (\Exception $e) {
            return $result->setData(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
