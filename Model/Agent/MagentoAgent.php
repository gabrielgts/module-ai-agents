<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Model\Agent;

use NeuronAI\Agent;
use NeuronAI\Chat\History\ChatHistoryInterface;

/**
 * Concrete Neuron AI Agent for use within Magento.
 *
 * Extends the base NeuronAI Agent. Configuration (provider, instructions, tools)
 * is applied through the inherited setters after creation via MagentoAgentFactory:
 *
 *   $agent->setAiProvider($provider);
 *   $agent->setInstructions($systemPrompt);
 *   $agent->addTool($tool);
 *
 * Chat history is injected via Magento DI (configured in di.xml), making it
 * swappable without modifying this class. Configure the concrete implementation
 * and its arguments in di.xml using a virtual type.
 *
 * The injected instance is assigned directly to the ResolveChatHistory trait's
 * $chatHistory property, which resolveChatHistory() picks up automatically.
 */
class MagentoAgent extends Agent
{
    /**
     * @param ChatHistoryInterface $chatHistory Injected by DI; defaults to InMemoryChatHistory.
     */
    public function __construct(ChatHistoryInterface $chatHistory)
    {
        $this->chatHistory = $chatHistory;
    }
}
