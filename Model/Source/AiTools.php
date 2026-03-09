<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Model\Source;

use Gtstudio\AiAgents\Api\GetAiToolsListInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Data\OptionSourceInterface;

class AiTools implements OptionSourceInterface
{
    public function __construct(
        private GetAiToolsListInterface $getAiToolsList,
        private SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
    }

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
