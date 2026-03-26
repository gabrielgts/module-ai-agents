<?php

namespace Gtstudio\AiAgents\Api\Data;

/**
 * Factory interface for creating AiToolsData instances.
 */
// phpcs:ignore Magento2.NamingConvention.InterfaceName.WrongInterfaceName
interface AiToolsDataFactory
{
    /**
     * Create a new AiToolsData instance.
     *
     * @param array $data
     * @return AiToolsInterface
     */
    public function create(array $data = []): AiToolsInterface;
}
