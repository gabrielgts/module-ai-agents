<?php

namespace Gtstudio\AiAgents\Block\Form\AiTools;

use Gtstudio\AiAgents\Api\Data\AiToolsInterface;
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
                __('Are you sure you want to delete this aitools?'),
                $this->getUrl(
                    '*/*/delete',
                    [AiToolsInterface::ENTITY_ID => $this->getEntityId()]
                )
            ),
            [],
            20
        );
    }
}
