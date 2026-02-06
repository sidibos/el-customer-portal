<?php

use App\Exceptions\ServiceException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AuthenticatePortalToken;
use App\Http\Middleware\InjectBearerTokenFromCookie;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->statefulApi();
        
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'cookie.bearer' => InjectBearerTokenFromCookie::class,
            'portal.token' => AuthenticatePortalToken::class,
        ]);

        $middleware->appendToGroup('api', InjectBearerTokenFromCookie::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ServiceException $e, Request $request) {
            Log::error('Service exception occurred', [
                'service' => $e->service(),
                'error_code' => $e->errorCode(),
                'message' => $e->getMessage(),
                'status' => $e->status(),
                'path' => $request->path(),
                'method' => $request->method(),
                'user_id' => optional($request->user())->id,
                'customer_id' => optional($request->user())->customer_id,
            ]);
    
            return response()->json([
                'message' => $e->getMessage(),
                'error' => $e->errorCode(),
                'service' => $e->service(),
            ], $e->status());
        });
    })->create();
