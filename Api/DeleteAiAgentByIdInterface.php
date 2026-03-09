<?php

namespace Gtstudio\AiAgents\Api;

use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Delete AiAgent by id Command.
 *
 * @api
 */
interface DeleteAiAgentByIdInterface
{
    /**
     * Delete AiAgent.
     * @param int $entityId
     * @return void
     * @throws CouldNotDeleteException
     */
    public function execute(int $entityId): void;
}
