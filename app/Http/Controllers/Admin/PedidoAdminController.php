<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoAdminController extends Controller
{
    public function index(Request $request)
    {
        $user  = auth()->user();
        $query = Pedido::with(['user', 'detalles.producto', 'pago']);

        if ($user->esBarista()) {
            $query->whereIn('estado', Pedido::$visiblesBarista);
        } elseif ($user->esCoordinadorDelivery()) {
            $query->whereIn('estado', Pedido::$visiblesDelivery);
        }
        // cajero, admin_sistema, admin_general: ven todos

        if ($request->estado) {
            $query->where('estado', $request->estado);
        }

        $pedidos = $query->latest()->paginate(20);
        return view('admin.pedidos.index', compact('pedidos', 'user'));
    }

    public function detalle(Pedido $pedido)
    {
        $pedido->load(['user', 'detalles.producto', 'pago', 'incidencias']);
        return view('admin.pedidos.detalle', compact('pedido'));
    }

    public function cambiarEstado(Request $request, Pedido $pedido)
    {
        $user        = auth()->user();
        $nuevoEstado = $request->validate(['estado' => 'required|string'])['estado'];

        if ($user->esBarista()) {
            abort_unless(in_array($nuevoEstado, Pedido::$estadosBarista), 403, 'Acción no permitida.');
        } elseif ($user->esCoordinadorDelivery()) {
            abort_unless(in_array($nuevoEstado, Pedido::$estadosDelivery), 403, 'Acción no permitida.');
        } elseif ($user->esCajero()) {
            // Cajero solo puede activar en_preparacion (al aprobar pago)
            abort_unless($nuevoEstado === 'en_preparacion', 403, 'Acción no permitida.');
        }
        // admin_general y admin_sistema pueden poner cualquier estado

        $pedido->update(['estado' => $nuevoEstado]);

        return response()->json([
            'success' => true,
            'estado'  => $nuevoEstado,
            'label'   => $pedido->fresh()->estado_label,
            'badge'   => $pedido->fresh()->estado_badge,
        ]);
    }
}
