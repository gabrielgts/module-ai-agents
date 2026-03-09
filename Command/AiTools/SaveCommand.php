<?php

namespace Gtstudio\AiAgents\Command\AiTools;

use Exception;
use Gtstudio\AiAgents\Api\Data\AiToolsInterface;
use Gtstudio\AiAgents\Api\SaveAiToolsInterface;
use Gtstudio\AiAgents\Model\AiToolsModel;
use Gtstudio\AiAgents\Model\AiToolsModelFactory;
use Gtstudio\AiAgents\Model\ResourceModel\AiToolsResource;
use Magento\Framework\Exception\CouldNotSaveException;
use Psr\Log\LoggerInterface;

/**
 * Save AiTools Command.
 */
class SaveCommand implements SaveAiToolsInterface
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
    public function execute(AiToolsInterface $aiTools): int
    {
        try {
            /** @var AiToolsModel $model */
            $model = $this->modelFactory->create();
            $model->addData($aiTools->getData());
            $model->setHasDataChanges(true);

            if (!$model->getData(AiToolsInterface::ENTITY_ID)) {
                $model->isObjectNew(true);
            }
            $this->resource->save($model);
        } catch (Exception $exception) {
            $this->logger->error(
                __('Could not save AiTools. Original message: {message}'),
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );
            throw new CouldNotSaveException(__('Could not save AiTools.'));
        }

        return (int)$model->getData(AiToolsInterface::ENTITY_ID);
    }
}
