<?php

namespace App\Contracts;

use App\Models\BillingPreference;

interface BillingPreferenceServiceInterface
{
    public function getForCustomer(int $customerId): BillingPreference;

    public function updateForCustomer(int $customerId, string $format): BillingPreference;
}