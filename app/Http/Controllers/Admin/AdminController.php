<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use App\Models\Reserva;

class AdminController extends Controller
{
    public function selectRol()
    {
        if (auth()->check() && auth()->user()->esAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.select_rol');
    }

    public function dashboard()
    {
        $user = auth()->user();

        $stats = [
            'pedidos_hoy'    => Pedido::whereDate('fecha', today())->count(),
            'pedidos_pendientes' => Pedido::where('estado', 'pendiente')->count(),
            'productos_stock_bajo' => Producto::where('stock', '<', 5)->where('estado', true)->count(),
            'ingresos_hoy'   => Pedido::whereDate('fecha', today())->where('estado', 'entregado')->sum('total'),
            'total_clientes' => User::where('rol', 'cliente')->count(),
            'reservas_hoy'   => Reserva::whereDate('fecha', today())->count(),
        ];

        $pedidos_recientes = Pedido::with(['user', 'detalles'])
            ->latest()
            ->take(10)
            ->get();

        $productos_bajo_stock = Producto::where('stock', '<', 5)
            ->where('estado', true)
            ->orderBy('stock')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pedidos_recientes', 'productos_bajo_stock', 'user'));
    }
}
