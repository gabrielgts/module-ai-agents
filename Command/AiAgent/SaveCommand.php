<?php

namespace Gtstudio\AiAgents\Command\AiAgent;

use Exception;
use Gtstudio\AiAgents\Api\Data\AiAgentInterface;
use Gtstudio\AiAgents\Api\SaveAiAgentInterface;
use Gtstudio\AiAgents\Model\AiAgentModel;
use Gtstudio\AiAgents\Model\AiAgentModelFactory;
use Gtstudio\AiAgents\Model\ResourceModel\AiAgentResource;
use Magento\Framework\Exception\CouldNotSaveException;
use Psr\Log\LoggerInterface;

/**
 * Save AiAgent Command.
 */
class SaveCommand implements SaveAiAgentInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var AiAgentModelFactory
     */
    private AiAgentModelFactory $modelFactory;

    /**
     * @var AiAgentResource
     */
    private AiAgentResource $resource;

    /**
     * @param LoggerInterface $logger
     * @param AiAgentModelFactory $modelFactory
     * @param AiAgentResource $resource
     */
    public function __construct(
        LoggerInterface $logger,
        AiAgentModelFactory $modelFactory,
        AiAgentResource $resource
    )
    {
        $this->logger = $logger;
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
    }

    /**
     * @inheritDoc
     */
    public function execute(AiAgentInterface $aiAgent): int
    {
        try {
            /** @var AiAgentModel $model */
            $model = $this->modelFactory->create();
            $model->addData($aiAgent->getData());
            $model->setHasDataChanges(true);

            if (!$model->getData(AiAgentInterface::ENTITY_ID)) {
                $model->isObjectNew(true);
            }
            $this->resource->save($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not save AiAgent. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotSaveException(__('Could not save AiAgent.'));
        }

        return (int)$model->getData(AiAgentInterface::ENTITY_ID);
    }
}
