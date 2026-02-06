<?php

namespace App\Contracts;

use App\Models\Meter;
use App\Models\Site;
use Illuminate\Support\Collection;

interface ConsumptionServiceInterface
{
    public function meterMonthly(Meter $meter, int $months = 6): Collection;

    public function siteMonthly(Site $site, int $months = 6): Collection;
}