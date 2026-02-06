<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Contracts\ConsumptionServiceInterface;
use Illuminate\Http\Request;

class SiteConsumptionController extends Controller
{
    /**
     * Return monthly consumption aggregated for a site (sum across meters).
     */
    public function index(
        Request $request, 
        Site $site, 
        ConsumptionServiceInterface $consumptionService
    )
    {
        $months = (int) $request->query('months', 6);

        return response()->json([
            'site_id'   => $site->id,
            'months'    => max(1, min($months, 24)),
            'data'      => $consumptionService->siteMonthly($site, $months),
        ]);
    }
}
