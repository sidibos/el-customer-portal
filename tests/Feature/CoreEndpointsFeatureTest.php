<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Meter;
use App\Models\MeterReading;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CoreEndpointsFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_dashboard_returns_summary(): void
    {
        $customer = Customer::factory()->create([
            'name' => 'Acme Energy Ltd',
            'last_bill_amount' => 123.45,
            'outstanding_balance' => 67.89,
        ]);

        $user = User::factory()->create(['customer_id' => $customer->id]);

        // create 2 sites
        $site1 = Site::factory()->create(['customer_id' => $customer->id]);
        $site2 = Site::factory()->create(['customer_id' => $customer->id]);

        // 3 active meters across sites, 1 inactive (should not count)
        Meter::factory()->create(['site_id' => $site1->id, 'is_active' => true]);
        Meter::factory()->create(['site_id' => $site1->id, 'is_active' => true]);
        Meter::factory()->create(['site_id' => $site2->id, 'is_active' => true]);
        Meter::factory()->create(['site_id' => $site2->id, 'is_active' => false]);

        Sanctum::actingAs($user);

        $res = $this->getJson('/api/dashboard');

        $res->assertOk()
            ->assertJson([
                'customerName' => 'Acme Energy Ltd',
                'sitesCount' => 2,
                'activeMetersCount' => 3,
                'lastBillAmount' => 123.45,
                'outstandingBalance' => 67.89,
            ]);
    }

    public function test_get_sites_lists_only_customers_sites(): void
    {
        $customerA = Customer::factory()->create();
        $userA = User::factory()->create(['customer_id' => $customerA->id]);

        $customerB = Customer::factory()->create();
        Site::factory()->count(2)->create(['customer_id' => $customerB->id]);

        $sitesA = Site::factory()->count(3)->create(['customer_id' => $customerA->id]);

        Sanctum::actingAs($userA);

        $res = $this->getJson('/api/sites');
        $res->assertOk();

        $ids = collect($res->json())->pluck('id')->all();

        $this->assertCount(3, $ids);
        foreach ($sitesA as $s) {
            $this->assertContains($s->id, $ids);
        }
    }

    public function test_get_site_meters_returns_meters_with_latest_reading(): void
    {
        $customer = Customer::factory()->create();
        $user = User::factory()->create(['customer_id' => $customer->id]);

        $site = Site::factory()->create(['customer_id' => $customer->id]);

        $meter = Meter::factory()->create([
            'site_id' => $site->id,
            'type' => 'electric',
            'is_active' => true,
        ]);

        // Two readings - latest should be the newer read_at
        MeterReading::factory()->create([
            'meter_id' => $meter->id,
            'reading' => 1000.000,
            'read_at' => now()->subDays(10),
        ]);

        $latest = MeterReading::factory()->create([
            'meter_id' => $meter->id,
            'reading' => 1100.250,
            'read_at' => now()->subDay(),
        ]);

        Sanctum::actingAs($user);

        $res = $this->getJson("/api/sites/{$site->id}/meters");
        
        $res->assertOk()
            ->assertJsonStructure([
                'site_id',
                'meters' => [
                    ['id', 'meterId', 'type', 'isActive', 'latestReading', 'lastUpdated']
                ],
            ]);

        $meters = $res->json('meters');
        $this->assertCount(1, $meters);

        $this->assertEquals($meter->id, $meters[0]['id']);
        $this->assertEquals('electric', $meters[0]['type']);
        $this->assertEquals(true, $meters[0]['isActive']);
        $this->assertEquals((float) $latest->reading, (float) $meters[0]['latestReading']);
        $this->assertNotEmpty($meters[0]['lastUpdated']);
    }

    public function test_get_meter_details_includes_latest_reading_and_site(): void
    {
        $customer = Customer::factory()->create();
        $user = User::factory()->create(['customer_id' => $customer->id]);

        $site = Site::factory()->create([
            'customer_id' => $customer->id,
            'name' => 'London HQ',
        ]);

        $meter = Meter::factory()->create([
            'site_id' => $site->id,
            'type' => 'gas',
            'is_active' => true,
        ]);

        MeterReading::factory()->create([
            'meter_id' => $meter->id,
            'reading' => 200.000,
            'read_at' => now()->subDays(7),
        ]);

        $latest = MeterReading::factory()->create([
            'meter_id' => $meter->id,
            'reading' => 250.500,
            'read_at' => now()->subHours(3),
        ]);

        Sanctum::actingAs($user);

        $res = $this->getJson("/api/meters/{$meter->id}");
        $res->assertOk()
            ->assertJsonStructure([
                'id',
                'meterId',
                'type',
                'isActive',
                'site' => ['id', 'name'],
                'latestReading',
                'lastUpdated',
            ]);

        $res->assertJsonPath('id', $meter->id);
        $res->assertJsonPath('type', 'gas');
        $res->assertJsonPath('site.id', $site->id);
        $res->assertJsonPath('site.name', 'London HQ');
        $this->assertEquals((float) $latest->reading, (float) $res->json('latestReading'));
        $this->assertNotEmpty($res->json('lastUpdated'));
    }
}