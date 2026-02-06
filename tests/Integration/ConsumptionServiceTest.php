<?php

namespace Tests\Integration;

use App\Contracts\ConsumptionServiceInterface;
use App\Models\Consumption;
use App\Models\Meter;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsumptionServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_site_monthly_aggregation(): void
    {
        $user = User::factory()->create();
        $site = Site::factory()->create(['customer_id' => $user->customer_id]);

        $m1 = Meter::factory()->create(['site_id' => $site->id]);
        $m2 = Meter::factory()->create(['site_id' => $site->id]);

        $month = now()->startOfMonth()->toDateString();

        Consumption::factory()->create(['meter_id' => $m1->id, 'month' => $month, 'usage' => 20]);
        Consumption::factory()->create(['meter_id' => $m2->id, 'month' => $month, 'usage' => 30]);

        /** @var ConsumptionServiceInterface $svc */
        $svc = app(ConsumptionServiceInterface::class);

        $rows = $svc->siteMonthly($site, 6);

        $row = $rows->firstWhere('month', $month);
        $this->assertNotNull($row);
        $this->assertEquals(50, (float) $row->usage);
    }
}
