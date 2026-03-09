<?php

namespace Gtstudio\AiAgents\Block\Form\AiAgent;

use Gtstudio\AiAgents\Api\Data\AiAgentInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Delete entity button.
 */
class Delete extends GenericButton implements ButtonProviderInterface
{
    /**
     * Retrieve Delete button settings.
     *
     * @return array
     */
    public function getButtonData(): array
    {
        if (!$this->getEntityId()) {
            return [];
        }

        return $this->wrapButtonSettings(
            __('Delete')->getText(),
            'delete',
            sprintf("deleteConfirm('%s', '%s')",
                __('Are you sure you want to delete this aiagent?'),
                $this->getUrl(
                    '*/*/delete',
                    [AiAgentInterface::ENTITY_ID => $this->getEntityId()]
                )
            ),
            [],
            20
        );
    }
}
