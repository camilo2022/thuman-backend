<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware) {

        // Tus alias
        $middleware->alias([
            'can' => \App\Http\Middleware\AccessRoleAndPermission::class,
        ]);

        // Middleware API
        $middleware->api(append: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            // \Illuminate\Routing\Middleware\ThrottleRequests::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (PostTooLargeException $e, $request) {
            return response()->json([
                'message' => 'El archivo es demasiado grande.',
                'attributes' => [
                    'video.file' => 'Video',
                ],
                'errors' => [
                    'video.file' => [
                        'El video no debe exceder los 20480 KB.'
                    ]
                ]
            ], 413);
        });
    })
    ->create();
