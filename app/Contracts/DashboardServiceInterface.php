<?php

namespace App\Contracts;

use App\Models\Customer;

interface DashboardServiceInterface
{
    public function overview(Customer $customer): array;
}