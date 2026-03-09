<?php

namespace Gtstudio\AiAgents\Mapper;

use Gtstudio\AiAgents\Api\Data\AiToolsInterface;
use Gtstudio\AiAgents\Api\Data\AiToolsInterfaceFactory;
use Gtstudio\AiAgents\Model\AiToolsModel;
use Magento\Framework\DataObject;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Converts a collection of AiTools entities to an array of data transfer objects.
 */
class AiToolsDataMapper
{
    /**
     * @var AiToolsInterfaceFactory
     */
    private AiToolsInterfaceFactory $entityDtoFactory;

    /**
     * @param AiToolsInterfaceFactory $entityDtoFactory
     */
    public function __construct(
        AiToolsInterfaceFactory $entityDtoFactory
    )
    {
        $this->entityDtoFactory = $entityDtoFactory;
    }

    /**
     * Map magento models to DTO array.
     *
     * @param AbstractCollection $collection
     *
     * @return array|AiToolsInterface[]
     */
    public function map(AbstractCollection $collection): array
    {
        $results = [];
        /** @var AiToolsModel $item */
        foreach ($collection->getItems() as $item) {
            /** @var AiToolsInterface|DataObject $entityDto */
            $entityDto = $this->entityDtoFactory->create();
            $entityDto->addData($item->getData());

            $results[] = $entityDto;
        }

        return $results;
    }
}
