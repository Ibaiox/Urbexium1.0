<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'no.banned'      => \App\Http\Middleware\NoBanned::class,
        'is.admin'       => \App\Http\Middleware\IsAdmin::class,
        'mantenimiento'  => \App\Http\Middleware\CheckMantenimiento::class,
    ]);
    // Aplica mantenimiento globalmente a todas las rutas web
    $middleware->web(append: [
        \App\Http\Middleware\CheckMantenimiento::class,
    ]);
})
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
