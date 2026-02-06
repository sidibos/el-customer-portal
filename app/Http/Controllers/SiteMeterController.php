<?php

namespace App\Http\Controllers;

use App\Models\Site;

class SiteMeterController extends Controller
{
    public function index(Site $site)
    {
        $meters = $site->meters()
            ->with(['latestReading'])
            ->orderBy('type')
            ->orderBy('meter_identifier')
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'meterId' => $m->meter_identifier,
                    'type' => $m->type,
                    'isActive' => (bool) $m->is_active,
                    'latestReading' => $m->latestReading?->reading,
                    'lastUpdated' => $m->latestReading?->read_at?->toISOString(),
                ];
            });

        return response()->json([
            'site_id' => $site->id,
            'meters' => $meters,
        ]);
    }
}