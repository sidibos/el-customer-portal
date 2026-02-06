<?php

namespace Tests\Feature;

use App\Models\Consumption;
use App\Models\Customer;
use App\Models\Meter;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ConsumptionEndpointsFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_meter_monthly_consumption_returns_rows(): void
    {
        $customer = Customer::factory()->create();
        $user = User::factory()->create(['customer_id' => $customer->id]);

        $site = Site::factory()->create(['customer_id' => $customer->id]);
        $meter = Meter::factory()->create(['site_id' => $site->id]);

        for ($i = 0; $i < 6; $i++) {
            Consumption::factory()->create([
                'meter_id' => $meter->id,
                'month' => now()->startOfMonth()->subMonths($i)->toDateString(),
                'consumption' => 10 + $i,
            ]);
        }

        Sanctum::actingAs($user);

        $res = $this->getJson("/api/meters/{$meter->id}/consumption?months=6");
        $res->assertOk()
            ->assertJsonStructure(['meter_id', 'months', 'data']);

        $this->assertCount(6, $res->json('data'));
    }

    public function test_site_monthly_consumption_aggregates_across_meters(): void
    {
        $customer = Customer::factory()->create();
        $user = User::factory()->create(['customer_id' => $customer->id]);

        $site = Site::factory()->create(['customer_id' => $customer->id]);

        $m1 = Meter::factory()->create(['site_id' => $site->id]);
        $m2 = Meter::factory()->create(['site_id' => $site->id]);

        $month = now()->startOfMonth()->toDateString();

        Consumption::factory()->create(['meter_id' => $m1->id, 'month' => $month, 'consumption' => 100]);
        Consumption::factory()->create(['meter_id' => $m2->id, 'month' => $month, 'consumption' => 50]);

        Sanctum::actingAs($user);

        $res = $this->getJson("/api/sites/{$site->id}/consumption?months=6");
        $res->assertOk();

        $data = $res->json('data');
        $this->assertNotEmpty($data);

        // IMPORTANT: month may come back as "2026-02-01 00:00:00" or "2026-02-01"
        // Normalize both to date string:
        $row = collect($data)->first(function ($r) use ($month) {
            return substr($r['month'], 0, 10) === $month;
        });

        $this->assertNotNull($row);
        $this->assertEquals(150, (float) $row['usage']);
    }
}