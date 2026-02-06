<?php

namespace Tests\Feature;

use App\Models\Meter;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DataIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_access_other_customers_site(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $siteB = Site::factory()->create(['customer_id' => $userB->customer_id]);

        Sanctum::actingAs($userA);

        // because of scoped route binding, it should look like "not found"
        $this->getJson("/api/sites/{$siteB->id}")
            ->assertNotFound();
    }

    public function test_user_cannot_access_other_customers_meter(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $siteB = Site::factory()->create(['customer_id' => $userB->customer_id]);
        $meterB = Meter::factory()->create(['site_id' => $siteB->id]);

        Sanctum::actingAs($userA);

        $this->getJson("/api/meters/{$meterB->id}")
            ->assertNotFound();
    }
}