<?php

declare(strict_types=1);

namespace Gtstudio\AiAgents\Model\Service;

use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Renders a cron input template by substituting supported variable tokens.
 *
 * Supported tokens:
 *   {{date}}        — current date, e.g. 2026-03-08
 *   {{datetime}}    — current date+time, e.g. 2026-03-08 14:00:00
 *   {{timestamp}}   — Unix timestamp
 *   {{store_name}}  — default store name
 *   {{store_url}}   — default store base URL
 */
class CronInputRenderer
{
    public function __construct(
        private readonly TimezoneInterface $timezone,
        private readonly StoreManagerInterface $storeManager
    ) {
    }

    public function render(string $template): string
    {
        $now = $this->timezone->date();

        try {
            $store     = $this->storeManager->getDefaultStoreView();
            $storeName = $store ? $store->getName() : '';
            $storeUrl  = $store ? $store->getBaseUrl() : '';
        } catch (\Exception) {
            $storeName = '';
            $storeUrl  = '';
        }

        $vars = [
            '{{date}}'       => $now->format('Y-m-d'),
            '{{datetime}}'   => $now->format('Y-m-d H:i:s'),
            '{{timestamp}}'  => (string) $now->getTimestamp(),
            '{{store_name}}' => $storeName,
            '{{store_url}}'  => $storeUrl,
        ];

        return str_replace(array_keys($vars), array_values($vars), $template);
    }
}
