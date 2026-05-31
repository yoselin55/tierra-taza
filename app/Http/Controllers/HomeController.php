<?php

namespace App\Http\Controllers;

use App\Models\Producto;

class HomeController extends Controller
{
    public function index()
    {
        // Café del día (el mejor valorado)
        $cafeDia = Producto::activos()->orderByDesc('rating')->first();

        // Productos destacados
        $destacados = Producto::activos()->orderByDesc('rating')->take(6)->get();

        return view('shop.home', compact('cafeDia', 'destacados'));
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
