<?php

namespace App\Services;

use App\Contracts\BillingPreferenceServiceInterface;
use App\Exceptions\BillingServiceException;
use App\Models\BillingPreference;
use Throwable;

class BillingPreferenceService implements BillingPreferenceServiceInterface
{
    public function getForCustomer(int $customerId): BillingPreference
    {
        try {
            return BillingPreference::query()->firstOrCreate(
                ['customer_id' => $customerId],
                ['format' => 'PDF']
            );
        } catch (Throwable $e) {
            throw new BillingServiceException(
                message: 'Failed to fetch billing preference.',
                previous: $e
            );
        }
    }

    public function updateForCustomer(int $customerId, string $format): BillingPreference
    {
        try {
            return BillingPreference::query()->updateOrCreate(
                ['customer_id' => $customerId],
                ['format' => $format]
            );
        } catch (Throwable $e) {
            throw new BillingServiceException(
                message: 'Failed to update billing preference.',
                previous: $e
            );
        }
    }
}