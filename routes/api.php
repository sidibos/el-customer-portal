<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactDetailsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SiteMeterController;
use App\Http\Controllers\MeterController;
use App\Http\Controllers\MeterConsumptionController;
use App\Http\Controllers\SiteConsumptionController;
use App\Http\Controllers\BillingPreferenceController;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware(['cookie.bearer', 'auth:sanctum'])->group(function () {
    Route::get('/user', [UserController::class, 'show'])->name('user.show');
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard.overview');

    Route::get('/sites', [SiteController::class, 'index'])->name('sites.list');
    Route::get('/sites/{site}', [SiteController::class, 'show'])->name('sites.show');
    Route::get('/sites/{site}/meters', [SiteMeterController::class, 'index'])->name('sites.meters.list');

    Route::get('/sites/{site}/consumption', [SiteConsumptionController::class, 'index'])
        ->name('view.site.monthly.consumption');

    Route::get('/meters/{meter}', [MeterController::class, 'show'])->name('meters.show');
    Route::get('/meters/{meter}/consumption', [MeterConsumptionController::class, 'index'])
        ->name('consumption.meter.monthly');

    Route::get('/billing-preferences', [BillingPreferenceController::class, 'show'])
        ->name('billing.preferences.show');
    Route::put('/billing-preferences', [BillingPreferenceController::class, 'update'])
        ->name('billing.preferences.update');

    Route::get('/contact-details', [ContactDetailsController::class, 'show'])
        ->name('user.contact.show');
    Route::put('/contact-details', [ContactDetailsController::class, 'update'])
        ->name('user.contact.update');
});

// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/auth/logout', [AuthController::class, 'logout']);

//     // 👤 User / Context
//     Route::get('/user', [UserController::class, 'show'])->name('user.show');
//     Route::get('/contact-details', [ContactDetailsController::class, 'show'])->name('user.contact.show');
//     Route::put('/contact-details', [ContactDetailsController::class, 'update'])->name('user.contact.update');

//      // 📊 Dashboard
//     Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard.overview');

//     // 🏢 Sites
//     Route::get('/sites', [SiteController::class, 'index'])->name('sites.list');
//     Route::get('/sites/{site}', [SiteController::class, 'show'])->name('sites.show');
//     Route::get('/sites/{site}/meters', [SiteMeterController::class, 'index'])->name('sites.meters.list');

//     // ⚡ Meters
//     Route::get('/meters/{meter}', [MeterController::class, 'show'])->name('meters.show');

//     // 📈 Consumption
//     Route::get('/sites/{site}/consumption', [SiteConsumptionController::class, 'index'])
//         ->name('site.consumption.view');
//     Route::get('/meters/{meter}/consumption', [MeterConsumptionController::class, 'index'])
//         ->name('meter.consumption.view');    

//     // 🧾 Billing Preferences (customer-level)
//     Route::get('/billing-preferences', [BillingPreferenceController::class, 'show'])
//         ->name('billing.preferences.show');
//     Route::put('/billing-preferences', [BillingPreferenceController::class, 'update'])
//         ->name('billing.preferences.update');    
// });

