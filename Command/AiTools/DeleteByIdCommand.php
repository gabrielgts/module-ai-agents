<?php

namespace Gtstudio\AiAgents\Command\AiTools;

use Exception;
use Gtstudio\AiAgents\Api\Data\AiToolsInterface;
use Gtstudio\AiAgents\Api\DeleteAiToolsByIdInterface;
use Gtstudio\AiAgents\Model\AiToolsModel;
use Gtstudio\AiAgents\Model\AiToolsModelFactory;
use Gtstudio\AiAgents\Model\ResourceModel\AiToolsResource;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

/**
 * Delete AiTools by id Command.
 */
class DeleteByIdCommand implements DeleteAiToolsByIdInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var AiToolsModelFactory
     */
    private AiToolsModelFactory $modelFactory;

    /**
     * @var AiToolsResource
     */
    private AiToolsResource $resource;

    /**
     * @param LoggerInterface $logger
     * @param AiToolsModelFactory $modelFactory
     * @param AiToolsResource $resource
     */
    public function __construct(
        LoggerInterface $logger,
        AiToolsModelFactory $modelFactory,
        AiToolsResource $resource
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
            /** @var AiToolsModel $model */
            $model = $this->modelFactory->create();
            $this->resource->load($model, $entityId, AiToolsInterface::ENTITY_ID);

            if (!$model->getData(AiToolsInterface::ENTITY_ID)) {
                throw new NoSuchEntityException(
                    __('Could not find AiTools with id: `%id`',
                        [
                            'id' => $entityId
                        ]
                    )
                );
            }

            $this->resource->delete($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not delete AiTools. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotDeleteException(__('Could not delete AiTools.'));
        }
    }
}
