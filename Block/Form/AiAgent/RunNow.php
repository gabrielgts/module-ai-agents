<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Block\Form\AiAgent;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class RunNow extends GenericButton implements ButtonProviderInterface
{
    /**
     * @param Context $context
     */
    // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    /**
     * Get button data configuration for the Run Now button.
     *
     * @return array
     */
    public function getButtonData(): array
    {
        if (!$this->getEntityId()) {
            return [];
        }

        return [
            'label'      => __('Run Now'),
            'class'      => 'action-secondary',
            'on_click'   => 'window.aiAgentRunNow && window.aiAgentRunNow()',
            'sort_order' => 20,
        ];
    }
}
