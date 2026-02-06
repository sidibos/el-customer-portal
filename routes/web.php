<?php
use App\Http\Controllers\PortalTokenAuthController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::get('/login', fn () => Inertia::render('Auth/Login'))->name('login');

// Token-based login for portal (sets HttpOnly cookie)
Route::post('/portal/login', [PortalTokenAuthController::class, 'login'])->name('portal.login');
Route::post('/portal/logout', [PortalTokenAuthController::class, 'logout'])->name('portal.logout');

Route::middleware(['portal.token'])->group(function () {
    // Inertia pages are now actually protected
    Route::get('/', fn () => redirect('/dashboard'));
    Route::get('/dashboard', fn () => Inertia::render('Dashboard'));
    Route::get('/sites', fn () => Inertia::render('Sites/Index'));
    Route::get('/sites/{site}/meters', fn (\App\Models\Site $site) =>
        Inertia::render('Sites/Meters', ['siteId' => $site->id])
    );
    Route::get('/meters/{meter}', fn (\App\Models\Meter $meter) =>
        Inertia::render('Meters/Show', ['meterId' => $meter->id])
    );
    Route::get('/billing', fn () => Inertia::render('Billing/Preferences'));
    Route::get('/contact', fn () => Inertia::render('User/Contact'));
});

