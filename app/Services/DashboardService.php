<?php

namespace App\Services;

use App\Contracts\DashboardServiceInterface;
use App\Exceptions\DashboardServiceException;
use App\Models\Customer;
use App\Models\Meter;
use App\Models\Site;
use Throwable;

class DashboardService implements DashboardServiceInterface
{
    public function overview(Customer $customer): array
    {
        try {
            $sitesCount = Site::query()
                ->where('customer_id', $customer->id)
                ->count();

            $activeMetersCount = Meter::query()
                ->whereHas('site', fn ($q) => $q->where('customer_id', $customer->id))
                ->where('is_active', true)
                ->count();

            return [
                'customerName' => $customer->name,
                'sitesCount' => $sitesCount,
                'activeMetersCount' => $activeMetersCount,
                'lastBillAmount' => (float) $customer->last_bill_amount,
                'outstandingBalance' => (float) $customer->outstanding_balance,
            ];
        } catch (Throwable $e) {
            throw new DashboardServiceException(
                message: 'Failed to build dashboard overview.',
                previous: $e
            );
        }
    }
}