<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Query\AiAgent;

use Gtstudio\AiAgents\Api\Data\AiAgentInterface;
use Gtstudio\AiAgents\Api\Data\AiAgentInterfaceFactory;
use Gtstudio\AiAgents\Api\GetAiAgentByCodeInterface;
use Gtstudio\AiAgents\Model\ResourceModel\AiAgentModel\AiAgentCollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Load a single AiAgent entity by its unique code field.
 */
class GetByCodeQuery implements GetAiAgentByCodeInterface
{
    public function __construct(
        private readonly AiAgentCollectionFactory $collectionFactory,
        private readonly AiAgentInterfaceFactory $entityDtoFactory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute(string $code): AiAgentInterface
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter(AiAgentInterface::CODE, $code);
        $model = $collection->getFirstItem();

        if (!$model->getId()) {
            throw new NoSuchEntityException(
                __('AI Agent with code "%1" not found.', $code)
            );
        }

        /** @var AiAgentInterface $entity */
        $entity = $this->entityDtoFactory->create();
        $entity->addData($model->getData());

        return $entity;
    }
}
