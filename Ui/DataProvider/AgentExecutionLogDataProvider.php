<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Ui\DataProvider;

use Gtstudio\AiAgents\Model\ResourceModel\AgentExecutionLog\AgentExecutionLogCollection;
use Gtstudio\AiAgents\Model\ResourceModel\AgentExecutionLog\AgentExecutionLogCollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

class AgentExecutionLogDataProvider extends AbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param AgentExecutionLogCollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        AgentExecutionLogCollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }

    /**
     * Get all execution log data as array.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->collection->toArray();
    }
}
