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
        // Headers de seguridad en todas las respuestas web
        $middleware->web(\App\Http\Middleware\SecurityHeaders::class);

        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'auth' => \App\Http\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Redirigir 419 (CSRF expirado) al login en lugar de mostrar error en blanco
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Sesión expirada. Recarga la página.'], 419);
            }
            // Si viene del panel admin, redirigir al login admin
            if (str_starts_with($request->path(), 'admin')) {
                return redirect()->route('admin.select_rol')
                    ->with('error', 'Tu sesión expiró. Por favor ingresa de nuevo.');
            }
            return redirect()->route('login')
                ->with('error', 'Tu sesión expiró. Por favor ingresa de nuevo.');
        });
    })->create();
