<?php

namespace Gtstudio\AiAgents\Api;

use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Delete AiTools by id Command.
 *
 * @api
 */
interface DeleteAiToolsByIdInterface
{
    /**
     * Delete AiTools.
     * @param int $entityId
     * @return void
     * @throws CouldNotDeleteException
     */
    public function execute(int $entityId): void;
}
