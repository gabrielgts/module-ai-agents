<?php

namespace Gtstudio\AiAgents\Mapper;

use Gtstudio\AiAgents\Api\Data\AiAgentInterface;
use Gtstudio\AiAgents\Api\Data\AiAgentInterfaceFactory;
use Gtstudio\AiAgents\Model\AiAgentModel;
use Magento\Framework\DataObject;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Converts a collection of AiAgent entities to an array of data transfer objects.
 */
class AiAgentDataMapper
{
    /**
     * @var AiAgentInterfaceFactory
     */
    private AiAgentInterfaceFactory $entityDtoFactory;

    /**
     * @param AiAgentInterfaceFactory $entityDtoFactory
     */
    public function __construct(
        AiAgentInterfaceFactory $entityDtoFactory
    )
    {
        $this->entityDtoFactory = $entityDtoFactory;
    }

    /**
     * Map magento models to DTO array.
     *
     * @param AbstractCollection $collection
     *
     * @return array|AiAgentInterface[]
     */
    public function map(AbstractCollection $collection): array
    {
        $results = [];
        /** @var AiAgentModel $item */
        foreach ($collection->getItems() as $item) {
            /** @var AiAgentInterface|DataObject $entityDto */
            $entityDto = $this->entityDtoFactory->create();
            $entityDto->addData($item->getData());

            $results[] = $entityDto;
        }

        return $results;
    }
}
