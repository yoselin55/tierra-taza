<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $periodo = $request->get('periodo', 'diario');
        $fecha   = $request->get('fecha', now()->toDateString());

        [$inicio, $fin] = match($periodo) {
            'mensual' => [now()->startOfMonth(), now()->endOfMonth()],
            'anual'   => [now()->startOfYear(), now()->endOfYear()],
            default   => [now()->startOfDay(), now()->endOfDay()],  // diario
        };

        if ($request->filled('fecha')) {
            $d = \Carbon\Carbon::parse($fecha);
            [$inicio, $fin] = match($periodo) {
                'mensual' => [$d->copy()->startOfMonth(), $d->copy()->endOfMonth()],
                'anual'   => [$d->copy()->startOfYear(), $d->copy()->endOfYear()],
                default   => [$d->copy()->startOfDay(), $d->copy()->endOfDay()],
            };
        }

        $pedidos = Pedido::with(['user', 'detalles.producto', 'pago'])
            ->whereBetween('fecha', [$inicio, $fin])
            ->orderByDesc('fecha')
            ->get();

        $stats = [
            'total_pedidos'    => $pedidos->count(),
            'ingresos_total'   => $pedidos->where('estado', 'entregado')->sum('total'),
            'pedidos_entregados' => $pedidos->where('estado', 'entregado')->count(),
            'ticket_promedio'  => $pedidos->where('estado', 'entregado')->avg('total') ?? 0,
        ];

        // Producto más vendido
        $productosMasVendidos = \App\Models\DetallePedido::selectRaw('producto_id, SUM(cantidad) as total_vendido')
            ->whereHas('pedido', fn($q) => $q->whereBetween('fecha', [$inicio, $fin]))
            ->groupBy('producto_id')
            ->orderByDesc('total_vendido')
            ->with('producto')
            ->take(5)
            ->get();

        return view('admin.reportes.index', compact(
            'stats', 'pedidos', 'periodo', 'fecha', 'productosMasVendidos'
        ));
    }
}
