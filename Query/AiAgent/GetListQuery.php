<?php

namespace Gtstudio\AiAgents\Query\AiAgent;

use Gtstudio\AiAgents\Api\Data\AiAgentSearchResultsInterface;
use Gtstudio\AiAgents\Api\Data\AiAgentSearchResultsInterfaceFactory;
use Gtstudio\AiAgents\Api\GetAiAgentListInterface;
use Gtstudio\AiAgents\Mapper\AiAgentDataMapper;
use Gtstudio\AiAgents\Model\ResourceModel\AiAgentModel\AiAgentCollection;
use Gtstudio\AiAgents\Model\ResourceModel\AiAgentModel\AiAgentCollectionFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

/**
 * Get AiAgent list by search criteria query.
 */
class GetListQuery implements GetAiAgentListInterface
{
    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @var AiAgentCollectionFactory
     */
    private AiAgentCollectionFactory $entityCollectionFactory;

    /**
     * @var AiAgentDataMapper
     */
    private AiAgentDataMapper $entityDataMapper;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var AiAgentSearchResultsInterfaceFactory
     */
    private AiAgentSearchResultsInterfaceFactory $searchResultFactory;

    /**
     * @param CollectionProcessorInterface $collectionProcessor
     * @param AiAgentCollectionFactory $entityCollectionFactory
     * @param AiAgentDataMapper $entityDataMapper
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param AiAgentSearchResultsInterfaceFactory $searchResultFactory
     */
    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        AiAgentCollectionFactory $entityCollectionFactory,
        AiAgentDataMapper $entityDataMapper,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        AiAgentSearchResultsInterfaceFactory $searchResultFactory
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
    public function execute(?SearchCriteriaInterface $searchCriteria = null): AiAgentSearchResultsInterface
    {
        /** @var AiAgentCollection $collection */
        $collection = $this->entityCollectionFactory->create();

        if ($searchCriteria === null) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        } else {
            $this->collectionProcessor->process($searchCriteria, $collection);
        }

        $entityDataObjects = $this->entityDataMapper->map($collection);

        /** @var AiAgentSearchResultsInterface $searchResult */
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setItems($entityDataObjects);
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }
}
