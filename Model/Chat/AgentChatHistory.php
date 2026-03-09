<?php
declare(strict_types=1);

namespace Gtstudio\AiAgents\Model\Chat;

use NeuronAI\Chat\History\InMemoryChatHistory;

/**
 * Token-limited chat history for Magento agents.
 *
 * Wraps InMemoryChatHistory with a fixed context window so that Magento's
 * DI compiler never needs to serialize the TokenCounter object — avoiding
 * the __set_state() issue that arises when passing constructor arguments
 * to third-party classes via virtual types in di.xml.
 */
class AgentChatHistory extends InMemoryChatHistory
{
    private const CONTEXT_WINDOW = 4000;

    public function __construct()
    {
        parent::__construct(self::CONTEXT_WINDOW);
    }
}
