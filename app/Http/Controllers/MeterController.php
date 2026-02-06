<?php

namespace App\Http\Controllers;

use App\Models\Meter;

class MeterController extends Controller
{
    public function show(Meter $meter)
    {
        $meter->load(['latestReading', 'site']);

        return response()->json([
            'id' => $meter->id,
            'meterId' => $meter->meter_identifier,
            'type' => $meter->type,
            'isActive' => (bool) $meter->is_active,
            'site' => [
                'id' => $meter->site->id,
                'name' => $meter->site->name,
            ],
            'latestReading' => $meter->latestReading?->reading,
            'lastUpdated' => $meter->latestReading?->read_at?->toISOString(),
        ]);
    }
}