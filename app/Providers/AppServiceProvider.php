<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Compartir el conteo del carrito globalmente en las vistas
        View::composer('*', function ($view) {
            $cartCount = 0;
            if (session()->has('carrito')) {
                $cartCount = array_sum(array_column(session('carrito'), 'cantidad'));
            }
            $view->with('cartCount', $cartCount);
        });
    }
}
