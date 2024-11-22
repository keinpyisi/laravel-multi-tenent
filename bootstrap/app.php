<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\SetTenantFromPath;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__ . '/../routes/web.php',
            __DIR__ . '/../routes/admin.php',
            __DIR__ . '/../routes/tenant.php',
        ],
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: 'api',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'set.tenant' => SetTenantFromPath::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withProviders([
        // ... other service providers ...
        App\Providers\SchemaServiceProvider::class,
    ])
    ->create();
