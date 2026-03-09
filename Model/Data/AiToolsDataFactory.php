<?php

namespace Gtstudio\AiAgents\Model\Data;

use Gtstudio\AiAgents\Api\Data\AiToolsDataFactory as AiToolsDataFactoryInterface;
use Magento\Framework\ObjectManager\ObjectManager;

/**
 * Factory for AiToolsData instances.
 */
class AiToolsDataFactory implements AiToolsDataFactoryInterface
{
    /**
     * @var ObjectManager
     */
    private ObjectManager $objectManager;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Create a new AiToolsData instance.
     *
     * @param array $data
     * @return AiToolsData
     */
    public function create(array $data = []): AiToolsData
    {
        return $this->objectManager->create(AiToolsData::class, $data);
    }
}
