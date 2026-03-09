<?php

namespace Gtstudio\AiAgents\Model;

use Gtstudio\AiAgents\Api\Data\AiToolsSearchResultsInterfaceFactory;
use Magento\Framework\ObjectManager\ObjectManager;

/**
 * Factory for AiToolsSearchResults instances.
 */
class AiToolsSearchResultsFactory implements AiToolsSearchResultsInterfaceFactory
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
     * Create a new AiToolsSearchResults instance.
     *
     * @param array $data
     * @return AiToolsSearchResults
     */
    public function create(array $data = []): AiToolsSearchResults
    {
        return $this->objectManager->create(AiToolsSearchResults::class, $data);
    }
}

