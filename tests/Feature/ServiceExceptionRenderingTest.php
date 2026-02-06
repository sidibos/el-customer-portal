<?php

namespace Tests\Feature;

use App\Exceptions\BillingServiceException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ServiceExceptionRenderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_exception_is_rendered_as_json(): void
    {
        Route::get('/_test/service-exception', function () {
            throw new BillingServiceException('Boom');
        });

        $res = $this->getJson('/_test/service-exception');

        $res->assertStatus(500)
            ->assertJsonPath('message', 'Boom')
            ->assertJsonPath('error', 'billing_service_error')
            ->assertJsonPath('service', 'BillingPreferenceService');
    }
}