<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateBillingPreferenceRequest;
use App\Models\BillingPreference;
use Illuminate\Http\Request;

class BillingPreferenceController extends Controller
{
    public function show(Request $request)
    {
        $customerId = $request->user()->customer_id;

        $pref = BillingPreference::query()->firstOrCreate(
            ['customer_id' => $customerId],
            ['format' => 'PDF']
        );

        return response()->json([
            'format' => $pref->format,
        ]);
    }

    public function update(UpdateBillingPreferenceRequest $request)
    {
        $customerId = $request->user()->customer_id;

        $pref = BillingPreference::query()->updateOrCreate(
            ['customer_id' => $customerId],
            ['format' => $request->validated('format')]
        );

        return response()->json([
            'message' => 'Billing preference updated',
            'format' => $pref->format,
        ]);
    }
}