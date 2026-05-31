<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Recurso;
use Illuminate\Http\Request;

class ReservaAdminController extends Controller
{
    public function index(Request $request)
    {
        Reserva::liberarVencidas();

        $query = Reserva::with(['user', 'recurso'])->latest();

        if ($request->estado) {
            $query->where('estado', $request->estado);
        }
        if ($request->fecha) {
            $query->whereDate('fecha', $request->fecha);
        }

        $reservas = $query->paginate(20);

        return view('admin.reservas.index', compact('reservas'));
    }

    public function cambiarEstado(Request $request, Reserva $reserva)
    {
        $request->validate(['estado' => 'required|in:pendiente,confirmada,cancelada,completada']);

        $reserva->update(['estado' => $request->estado]);

        if (in_array($request->estado, ['cancelada', 'completada'])) {
            $tieneOtraActiva = Reserva::where('recurso_id', $reserva->recurso_id)
                ->whereIn('estado', ['confirmada', 'pendiente'])
                ->where('id', '!=', $reserva->id)
                ->exists();

            if (!$tieneOtraActiva) {
                $reserva->recurso->update(['estado' => 'disponible']);
            }
        } elseif (in_array($request->estado, ['confirmada', 'pendiente'])) {
            $reserva->recurso->update(['estado' => 'ocupado']);
        }

        return back()->with('success', 'Estado de reserva actualizado.');
    }

    public function liberarVencidas()
    {
        $n = Reserva::liberarVencidas();
        $msg = $n > 0
            ? "$n reserva(s) vencida(s) liberada(s) correctamente."
            : 'No había reservas vencidas pendientes.';

        return back()->with('success', $msg);
    }
}
