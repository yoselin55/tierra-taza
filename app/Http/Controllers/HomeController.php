<?php

namespace App\Http\Controllers;

use App\Models\Producto;

class HomeController extends Controller
{
    public function index()
    {
        // Ofertas activas del día
        $ofertas = Producto::activos()
            ->where('oferta_activa', true)
            ->where(function ($q) {
                $q->whereNull('oferta_hasta')
                  ->orWhere('oferta_hasta', '>=', today());
            })
            ->orderByDesc('rating')
            ->take(4)
            ->get();

        // Productos destacados
        $destacados = Producto::activos()->orderByDesc('rating')->take(6)->get();

        return view('shop.home', compact('ofertas', 'destacados'));
    }

    public function sobre()
    {
        return view('shop.sobre');
    }

    public function ubicacion()
    {
        return view('ubicacion.index');
    }
}
