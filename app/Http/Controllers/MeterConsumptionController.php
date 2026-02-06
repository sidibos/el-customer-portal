<?php

namespace App\Http\Controllers;

use App\Models\Meter;
use App\Contracts\ConsumptionServiceInterface;
use Illuminate\Http\Request;

class MeterConsumptionController extends Controller
{
    public function index(
        Request $request, 
        Meter $meter, 
        ConsumptionServiceInterface $consumptionService
    )
    {
        $months = (int) $request->query('months', 6);
        $months = max(1, min($months, 24));

        return response()->json([
            'meter_id'  => $meter->id,
            'months'    => $months,
            'data'      => $consumptionService->meterMonthly($meter, $months),
        ]);
    }
}