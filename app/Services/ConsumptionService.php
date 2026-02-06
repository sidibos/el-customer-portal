<?php

namespace App\Services;

use App\Contracts\ConsumptionServiceInterface;
use App\Exceptions\ConsumptionServiceException;
use App\Models\Consumption;
use App\Models\Meter;
use App\Models\Site;
use Illuminate\Support\Collection;
use Throwable;

class ConsumptionService implements ConsumptionServiceInterface
{
    public function meterMonthly(Meter $meter, int $months = 6): Collection
    {
        $months = $this->clampMonths($months);

        try {
            return $meter->consumptions()
                ->orderByDesc('month')
                ->limit($months)
                ->get(['month', 'consumption']);
        } catch (Throwable $e) {
            throw new ConsumptionServiceException(
                message: 'Failed to fetch meter consumption.',
                previous: $e
            );
        }
    }

    public function siteMonthly(Site $site, int $months = 6): Collection
    {
        $months = $this->clampMonths($months);

        try {
            return Consumption::query()
                ->whereIn('meter_id', $site->meters()->select('id'))
                ->selectRaw('month, SUM(consumption) as `usage`')
                ->groupBy('month')
                ->orderByDesc('month')
                ->limit($months)
                ->get();
        } catch (Throwable $e) {
            throw new ConsumptionServiceException(
                message: 'Failed to fetch site consumption.',
                previous: $e
            );
        }
    }

    private function clampMonths(int $months): int
    {
        return max(1, min($months, 24));
    }
}