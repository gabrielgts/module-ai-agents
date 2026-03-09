<?php

namespace Gtstudio\AiAgents\Query\AiTools;

use Gtstudio\AiAgents\Api\Data\AiToolsSearchResultsInterface;
use Gtstudio\AiAgents\Api\Data\AiToolsSearchResultsInterfaceFactory;
use Gtstudio\AiAgents\Api\GetAiToolsListInterface;
use Gtstudio\AiAgents\Mapper\AiToolsDataMapper;
use Gtstudio\AiAgents\Model\ResourceModel\AiToolsModel\AiToolsCollection;
use Gtstudio\AiAgents\Model\ResourceModel\AiToolsModel\AiToolsCollectionFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

/**
 * Get AiTools list by search criteria query.
 */
class GetListQuery implements GetAiToolsListInterface
{
    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @var AiToolsCollectionFactory
     */
    private AiToolsCollectionFactory $entityCollectionFactory;

    /**
     * @var AiToolsDataMapper
     */
    private AiToolsDataMapper $entityDataMapper;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var AiToolsSearchResultsInterfaceFactory
     */
    private AiToolsSearchResultsInterfaceFactory $searchResultFactory;

    /**
     * @param CollectionProcessorInterface $collectionProcessor
     * @param AiToolsCollectionFactory $entityCollectionFactory
     * @param AiToolsDataMapper $entityDataMapper
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param AiToolsSearchResultsInterfaceFactory $searchResultFactory
     */
    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        AiToolsCollectionFactory $entityCollectionFactory,
        AiToolsDataMapper $entityDataMapper,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        AiToolsSearchResultsInterfaceFactory $searchResultFactory
    )
    {
        $this->collectionProcessor = $collectionProcessor;
        $this->entityCollectionFactory = $entityCollectionFactory;
        $this->entityDataMapper = $entityDataMapper;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->searchResultFactory = $searchResultFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute(?SearchCriteriaInterface $searchCriteria = null): AiToolsSearchResultsInterface
    {
        /** @var AiToolsCollection $collection */
        $collection = $this->entityCollectionFactory->create();

        if ($searchCriteria === null) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        } else {
            $this->collectionProcessor->process($searchCriteria, $collection);
        }

        $entityDataObjects = $this->entityDataMapper->map($collection);

        /** @var AiToolsSearchResultsInterface $searchResult */
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setItems($entityDataObjects);
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }
}
