<?php

namespace Gtstudio\AiAgents\Api;

use Gtstudio\AiAgents\Api\Data\AiToolsInterface;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Save AiTools Command.
 *
 * @api
 */
interface SaveAiToolsInterface
{
    /**
     * Save AiTools.
     * @param \Gtstudio\AiAgents\Api\Data\AiToolsInterface $aiTools
     * @return int
     * @throws CouldNotSaveException
     */
    public function execute(AiToolsInterface $aiTools): int;
}
