<?php

namespace App\Http\Controllers;

use App\Models\Promocion;

class PromocionController extends Controller
{
    public function show(Promocion $promocion)
    {
        abort_unless($promocion->estaVigente(), 404);

        $productos = $promocion->productos()
            ->activos()
            ->where('oferta_activa', true)
            ->orderByDesc('rating')
            ->get();

        return view('shop.promocion', compact('promocion', 'productos'));
    }
}
