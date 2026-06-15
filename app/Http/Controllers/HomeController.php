<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Promocion;

class HomeController extends Controller
{
    public function index()
    {
        // Promociones vigentes con al menos 1 producto activo en oferta
        $promociones = Promocion::where('activa', true)
            ->where(function ($q) {
                $q->whereNull('fecha_inicio')->orWhere('fecha_inicio', '<=', today());
            })
            ->where(function ($q) {
                $q->whereNull('fecha_fin')->orWhere('fecha_fin', '>=', today());
            })
            ->withCount(['productos as productos_activos_count' => function ($q) {
                $q->where('estado', true)->where('oferta_activa', true);
            }])
            ->with(['productos' => function ($q) {
                $q->where('estado', true)->where('oferta_activa', true)->orderByDesc('rating')->limit(1);
            }])
            ->having('productos_activos_count', '>', 0)
            ->latest()
            ->get();

        // Productos destacados
        $destacados = Producto::activos()->orderByDesc('rating')->take(6)->get();

        return view('shop.home', compact('promociones', 'destacados'));
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
