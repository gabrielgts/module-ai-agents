<?php

namespace Gtstudio\AiAgents\Command\AiAgent;

use Exception;
use Gtstudio\AiAgents\Api\Data\AiAgentInterface;
use Gtstudio\AiAgents\Api\DeleteAiAgentByIdInterface;
use Gtstudio\AiAgents\Model\AiAgentModel;
use Gtstudio\AiAgents\Model\AiAgentModelFactory;
use Gtstudio\AiAgents\Model\ResourceModel\AiAgentResource;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

/**
 * Delete AiAgent by id Command.
 */
class DeleteByIdCommand implements DeleteAiAgentByIdInterface
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
    public function execute(int $entityId): void
    {
        try {
            /** @var AiAgentModel $model */
            $model = $this->modelFactory->create();
            $this->resource->load($model, $entityId, AiAgentInterface::ENTITY_ID);

            if (!$model->getData(AiAgentInterface::ENTITY_ID)) {
                throw new NoSuchEntityException(
                    __('Could not find AiAgent with id: `%id`',
                        [
                            'id' => $entityId
                        ]
                    )
                );
            }

            $this->resource->delete($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not delete AiAgent. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotDeleteException(__('Could not delete AiAgent.'));
        }
    }
}
