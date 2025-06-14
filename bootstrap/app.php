<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->alias([
        //     'auth'     => \App\Http\Middleware\Authenticate::class,
        //     'permiso'  => \App\Http\Middleware\CheckPermiso::class,
        //     'rol'      => \App\Http\Middleware\CheckRol::class,
        //     'verificar.activacion' => \App\Http\Middleware\VerificarActivacion::class, 
        // ]);
        //Nicoide
        //falta el middleware de autenticación
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
