<?php

namespace Gtstudio\AiAgents\Model\Data;

use Gtstudio\AiAgents\Api\Data\AiAgentDataFactory as AiAgentDataFactoryInterface;
use Magento\Framework\ObjectManager\ObjectManager;

/**
 * Factory for AiAgentData instances.
 */
class AiAgentDataFactory implements AiAgentDataFactoryInterface
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
     * Create a new AiAgentData instance.
     *
     * @param array $data
     * @return AiAgentData
     */
    public function create(array $data = []): AiAgentData
    {
        return $this->objectManager->create(AiAgentData::class, $data);
    }
}
