<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FunnelTracker
{
    public const EVENTS = [
        'primary_cta_clicked',
        'demo_started',
        'demo_completed',
        'registration_started',
        'registration_completed',
        'store_setup_completed',
        'first_campaign_created',
        'first_customer_card_issued',
        'first_stamp_added',
    ];

    /**
     * Record a provider-neutral funnel event without personal customer information.
     */
    public function track(string $event, array $properties = []): void
    {
        if (!config('loyalty.analytics.enabled', true) || !in_array($event, self::EVENTS, true)) {
            return;
        }

        // Strip any accidental PII keys before emitting
        $safeProperties = collect($properties)
            ->except(['name', 'email', 'phone', 'password', 'ip', 'user_agent'])
            ->all();

        $payload = [
            'event' => $event,
            'properties' => $safeProperties,
            'locale' => app()->getLocale(),
            'timestamp' => now()->toIso8601String(),
        ];

        $driver = config('loyalty.analytics.driver', 'log');

        if ($driver === 'webhook' && ($url = config('loyalty.analytics.webhook_url'))) {
            try {
                Http::timeout(2)->post($url, $payload);
            } catch (\Throwable $e) {
                Log::warning('Funnel analytics webhook failed', ['event' => $event, 'error' => $e->getMessage()]);
            }

            return;
        }

        Log::info('funnel.event', $payload);
    }
}
