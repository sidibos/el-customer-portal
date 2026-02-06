<?php

namespace App\Http\Controllers;

use App\Contracts\DashboardServiceInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function show(Request $request, DashboardServiceInterface $dashboardService)
    {
        return response()->json(
            $dashboardService->overview($request->user()->customer)
        );
    }
}