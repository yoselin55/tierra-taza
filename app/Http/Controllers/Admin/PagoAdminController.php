<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PagoAdminController extends Controller
{
    public function index(Request $request)
    {
        // Solo cajero y admin_general
        abort_unless(
            in_array(auth()->user()->rol, ['cajero', 'admin_general']),
            403
        );

        $query = Pago::with(['pedido.user', 'pedido.detalles.producto'])->latest();

        if ($request->estado) {
            $query->where('estado', $request->estado);
        }

        $pagos = $query->paginate(20);
        return view('admin.pagos.index', compact('pagos'));
    }

    public function validar(Request $request, Pago $pago)
    {
        abort_unless(
            in_array(auth()->user()->rol, ['cajero', 'admin_general']),
            403
        );

        $request->validate([
            'accion'       => 'required|in:aprobar,rechazar',
            'notas_cajero' => 'nullable|string|max:300',
        ]);

        if ($request->accion === 'aprobar') {
            $pago->update([
                'estado'       => 'completado',
                'notas_cajero' => $request->notas_cajero,
                'aprobado_en'  => now(),
            ]);
            $pago->pedido->update(['estado' => 'en_preparacion']);
            return back()->with('success', "Pago aprobado. Pedido #{$pago->pedido_id} pasa a preparación.");
        }

        $pago->update(['estado' => 'rechazado', 'notas_cajero' => $request->notas_cajero]);
        $pago->pedido->update(['estado' => 'cancelado']);
        return back()->with('success', 'Pago rechazado. Pedido cancelado.');
    }

    public function comprobante(Pago $pago)
    {
        abort_unless(
            in_array(auth()->user()->rol, ['cajero', 'admin_general']) ||
            (auth()->id() === $pago->pedido->user_id),
            403
        );
        abort_unless($pago->estado === 'completado', 403, 'El pago aún no ha sido aprobado.');

        $pago->load(['pedido.user', 'pedido.detalles.producto']);
        return view('comprobante', compact('pago'));
    }
}
