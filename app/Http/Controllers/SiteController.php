<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index(Request $request)
    {
        $customerId = $request->user()->customer_id;

        $sites = Site::query()
            ->where('customer_id', $customerId)
            ->orderBy('name')
            ->get(['id', 'name', 'address', 'customer_id']);

        return response()->json($sites);
    }

    public function show(Site $site)
    {
        // $site already scoped by route binding
        return response()->json([
            'id' => $site->id,
            'name' => $site->name,
            'address' => $site->address,
            'customer_id' => $site->customer_id,
        ]);
    }
}