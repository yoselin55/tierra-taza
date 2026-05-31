<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    public function index()
    {
        $carrito  = session('carrito', []);
        $total    = array_sum(array_map(fn($item) => $item['precio'] * $item['cantidad'], $carrito));
        return view('shop.carrito', compact('carrito', 'total'));
    }

    public function agregar(Request $request, Producto $producto)
    {
        if (!$producto->hayStock()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Producto sin stock'], 400);
            }
            return back()->with('error', 'Producto sin stock disponible.');
        }

        $carrito = session('carrito', []);
        $id = $producto->id;

        if (isset($carrito[$id])) {
            $carrito[$id]['cantidad']++;
        } else {
            $carrito[$id] = [
                'id'       => $producto->id,
                'nombre'   => $producto->nombre,
                'precio'   => $producto->precio,
                'cantidad' => 1,
                'imagen'   => $producto->imagen_url,
            ];
        }

        session(['carrito' => $carrito]);

        $total = array_sum(array_map(fn($item) => $item['precio'] * $item['cantidad'], $carrito));
        $count = array_sum(array_column($carrito, 'cantidad'));

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'count'   => $count,
                'total'   => number_format($total, 2),
                'message' => "{$producto->nombre} agregado al carrito.",
            ]);
        }

        return back()->with('success', "{$producto->nombre} agregado al carrito.");
    }

    public function actualizar(Request $request, $id)
    {
        $carrito   = session('carrito', []);
        $cantidad  = (int) $request->cantidad;

        if ($cantidad <= 0) {
            unset($carrito[$id]);
        } else {
            $producto = Producto::find($id);
            if ($producto && $cantidad <= $producto->stock) {
                $carrito[$id]['cantidad'] = $cantidad;
            }
        }

        session(['carrito' => $carrito]);

        $total = array_sum(array_map(fn($item) => $item['precio'] * $item['cantidad'], $carrito));
        $count = array_sum(array_column($carrito, 'cantidad'));

        return response()->json([
            'success'  => true,
            'total'    => number_format($total, 2),
            'count'    => $count,
            'subtotal' => isset($carrito[$id]) ? number_format($carrito[$id]['precio'] * $carrito[$id]['cantidad'], 2) : '0.00',
        ]);
    }

    public function eliminar($id)
    {
        $carrito = session('carrito', []);
        unset($carrito[$id]);
        session(['carrito' => $carrito]);

        $total = array_sum(array_map(fn($item) => $item['precio'] * $item['cantidad'], $carrito));
        $count = array_sum(array_column($carrito, 'cantidad'));

        return response()->json([
            'success' => true,
            'total'   => number_format($total, 2),
            'count'   => $count,
        ]);
    }

    public function vaciar()
    {
        session()->forget('carrito');
        return redirect()->route('carrito')->with('success', 'Carrito vaciado.');
    }
}
