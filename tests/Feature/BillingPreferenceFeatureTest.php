<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BillingPreferenceFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_billing_preference_persists(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $res = $this->putJson('/api/billing-preferences', [
            'format' => 'CSV',
        ]);

        $res->assertOk()
            ->assertJsonPath('format', 'CSV');

        $this->assertDatabaseHas('billing_preferences', [
            'customer_id' => $user->customer_id,
            'format' => 'CSV',
        ]);
    }

    public function test_invalid_billing_format_returns_422(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $res = $this->putJson('/api/billing-preferences', [
            'format' => 'INVALID',
        ]);

        $res->assertStatus(422)
            ->assertJsonValidationErrors(['format']);
    }
}