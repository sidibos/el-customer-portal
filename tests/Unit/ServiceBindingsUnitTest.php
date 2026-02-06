<?php

namespace Tests\Unit;

use App\Contracts\BillingPreferenceServiceInterface;
use App\Contracts\ConsumptionServiceInterface;
use App\Contracts\DashboardServiceInterface;
use App\Contracts\UserServiceInterface;
use App\Services\BillingPreferenceService;
use App\Services\ConsumptionService;
use App\Services\DashboardService;
use App\Services\UserService;
use Tests\TestCase;

class ServiceBindingsUnitTest extends TestCase
{
    public function test_contracts_are_bound_to_services(): void
    {
        $this->assertInstanceOf(ConsumptionService::class, app(ConsumptionServiceInterface::class));
        $this->assertInstanceOf(DashboardService::class, app(DashboardServiceInterface::class));
        $this->assertInstanceOf(BillingPreferenceService::class, app(BillingPreferenceServiceInterface::class));
        $this->assertInstanceOf(UserService::class, app(UserServiceInterface::class));
    }
}