<?php

namespace Gtstudio\AiAgents\Ui\DataProvider;

use Gtstudio\AiAgents\Api\Data\AiAgentInterface;
use Gtstudio\AiAgents\Api\GetAiAgentListInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use Magento\Ui\DataProvider\SearchResultFactory;

/**
 * DataProvider component.
 */
class AiAgentDataProvider extends DataProvider
{
    /**
     * @var GetAiAgentListInterface
     */
    private GetAiAgentListInterface $getListQuery;

    /**
     * @var SearchResultFactory
     */
    private SearchResultFactory $searchResultFactory;

    /**
     * @var array
     */
    private $loadedData = [];

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param ReportingInterface $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param GetAiAgentListInterface $getListQuery
     * @param SearchResultFactory $searchResultFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        GetAiAgentListInterface $getListQuery,
        SearchResultFactory $searchResultFactory,
        array $meta = [],
        array $data = []
    )
    {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
        $this->getListQuery = $getListQuery;
        $this->searchResultFactory = $searchResultFactory;
    }

    /**
     * Returns searching result.
     *
     * @return SearchResultFactory
     */
    public function getSearchResult()
    {
        $searchCriteria = $this->getSearchCriteria();
        $result = $this->getListQuery->execute($searchCriteria);

        return $this->searchResultFactory->create(
            $result->getItems(),
            $result->getTotalCount(),
            $searchCriteria,
            AiAgentInterface::ENTITY_ID
        );
    }

    /**
     * Get data.
     *
     * @return array
     */
    public function getData(): array
    {
        if ($this->loadedData) {
            return $this->loadedData;
        }
        $this->loadedData = parent::getData();
        $itemsById = [];

        foreach ($this->loadedData['items'] as $item) {
            $itemsById[(int)$item[AiAgentInterface::ENTITY_ID]] = $item;
        }

        if ($id = $this->request->getParam(AiAgentInterface::ENTITY_ID)) {
            $item = $itemsById[(int)$id];
            $item[AiAgentInterface::TOOLS] = $this->decodeTools($item[AiAgentInterface::TOOLS] ?? null);
            $this->loadedData['entity'] = $item;
        }

        return $this->loadedData;
    }

    /**
     * Decode comma-separated tools string into an array for the multiselect component.
     *
     * @param string|null $tools
     * @return array
     */
    private function decodeTools(?string $tools): array
    {
        if (empty($tools)) {
            return [];
        }
        return explode(',', $tools);
    }
}
