<?php

namespace Gtstudio\AiAgents\Model;

use Gtstudio\AiAgents\Api\Data\AiAgentSearchResultsInterfaceFactory;
use Magento\Framework\ObjectManager\ObjectManager;

/**
 * Factory for AiAgentSearchResults instances.
 */
class AiAgentSearchResultsFactory implements AiAgentSearchResultsInterfaceFactory
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
     * Create a new AiAgentSearchResults instance.
     *
     * @param array $data
     * @return AiAgentSearchResults
     */
    public function create(array $data = []): AiAgentSearchResults
    {
        return $this->objectManager->create(AiAgentSearchResults::class, $data);
    }
}
