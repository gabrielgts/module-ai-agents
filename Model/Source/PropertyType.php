<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class PropertyType implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        return [
            ['value' => '', 'label' => __('-- Select Type --')],
            ['value' => 'string', 'label' => __('String')],
            ['value' => 'integer', 'label' => __('Integer')],
            ['value' => 'number', 'label' => __('Number')],
            ['value' => 'boolean', 'label' => __('Boolean')],
            ['value' => 'array', 'label' => __('Array')],
            ['value' => 'object', 'label' => __('Object')],
        ];
    }
}
