<?php

namespace App\Http\Middleware;

use App\Models\Site;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        return [
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'email' => $user->email,
                    'type' => $user->type,
                    'phone' => $user->phone,
                    'customer_id' => $user->customer_id,
                ] : null,
                'customer' => $user?->customer ? [
                    'id' => $user->customer->id,
                    'name' => $user->customer->name,
                ] : null,
            ],
            'sites' => $user
                ? Site::where('customer_id', $user->customer_id)
                    ->orderBy('name')
                    ->get(['id', 'name'])
                : [],
            ...parent::share($request),
            //
        ];
    }
}
