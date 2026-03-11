<?php

namespace Gtstudio\AiAgents\Ui\DataProvider;

use Gtstudio\AiAgents\Api\Data\AiToolsInterface;
use Gtstudio\AiAgents\Api\GetAiToolsListInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use Magento\Ui\DataProvider\SearchResultFactory;

/**
 * DataProvider component.
 */
class AiToolsDataProvider extends DataProvider
{
    /**
     * @var GetAiToolsListInterface
     */
    private GetAiToolsListInterface $getListQuery;

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
     * @param GetAiToolsListInterface $getListQuery
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
        GetAiToolsListInterface $getListQuery,
        SearchResultFactory $searchResultFactory,
        array $meta = [],
        array $data = []
    ) {
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
            AiToolsInterface::ENTITY_ID
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

        $rawData  = parent::getData();
        $entityId = $this->request->getParam(AiToolsInterface::ENTITY_ID);

        if ($entityId) {
            // Edit form: find the item and nest all fields under 'general' so
            // they align with the form's dataScope="data.general".
            $itemsById = [];
            foreach ($rawData['items'] ?? [] as $item) {
                $itemsById[(int)$item[AiToolsInterface::ENTITY_ID]] = $item;
            }

            $item = $itemsById[(int)$entityId] ?? [];
            $item[AiToolsInterface::PROPERTIES] = $this->decodeProperties(
                $item[AiToolsInterface::PROPERTIES] ?? null
            );

            $this->loadedData = [(int)$entityId => ['general' => $item]];
        } else {
            // Listing grid: return the standard {totalRecords, items} structure.
            $this->loadedData = $rawData;
        }

        return $this->loadedData;
    }

    /**
     * Decode JSON-encoded properties string into an array for the dynamic rows component.
     *
     * The `required` field is stored as a boolean in JSON but the checkbox
     * valueMap expects the strings "1" / "0".
     *
     * @param string|null $properties
     * @return array
     */
    private function decodeProperties(?string $properties): array
    {
        if (empty($properties)) {
            return [];
        }
        $decoded = json_decode($properties, true);
        if (!is_array($decoded)) {
            return [];
        }

        return array_values(array_map(static function (array $prop, int $index): array {
            $recordId = $prop['record_id'] ?? $index;

            return [
                'record_id'   => $recordId,
                '_id'         => $recordId,
                'name'        => $prop['name'] ?? '',
                'type'        => $prop['type'] ?? '',
                'description' => $prop['description'] ?? '',
                'required'    => !empty($prop['required']) ? '1' : '0',
                'position'    => $prop['position'] ?? $index,
            ];
        }, $decoded, array_keys($decoded)));
    }
}
