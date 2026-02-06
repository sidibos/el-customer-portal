<?php

namespace Tests\Integration;

use App\Contracts\BillingPreferenceServiceInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillingPreferenceServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_and_get_for_customer(): void
    {
        $user = User::factory()->create();

        /** @var BillingPreferenceServiceInterface $svc */
        $svc = app(BillingPreferenceServiceInterface::class);

        $svc->updateForCustomer($user->customer_id, 'EDI');

        $pref = $svc->getForCustomer($user->customer_id);

        $this->assertEquals('EDI', $pref->format);
        $this->assertDatabaseHas('billing_preferences', [
            'customer_id' => $user->customer_id,
            'format' => 'EDI',
        ]);
    }
}
