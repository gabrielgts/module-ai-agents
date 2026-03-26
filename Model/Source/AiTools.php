<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Model\Source;

use Gtstudio\AiAgents\Api\GetAiToolsListInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Data\OptionSourceInterface;

class AiTools implements OptionSourceInterface
{
    /**
     * @param GetAiToolsListInterface $getAiToolsList
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        private GetAiToolsListInterface $getAiToolsList,
        private SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
    }

    /**
     * Return option array of all AI tools.
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $result = $this->getAiToolsList->execute($searchCriteria);

        $options = [];
        foreach ($result->getItems() as $tool) {
            $options[] = [
                'value' => $tool->getEntityId(),
                'label' => $tool->getCode(),
            ];
        }

        return $options;
    }
}
