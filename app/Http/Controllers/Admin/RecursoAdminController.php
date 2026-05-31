<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use App\Models\Recurso;
use Illuminate\Http\Request;

class RecursoAdminController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->esAdminGeneral(), 403);

        $mesas      = Recurso::where('tipo', 'mesa')->orderBy('numero')->get();
        $coworkings = Recurso::where('tipo', 'coworking')->orderBy('numero')->get();
        $reservasHabilitadas = Configuracion::reservasHabilitadas();

        return view('admin.recursos.mapa', compact('mesas', 'coworkings', 'reservasHabilitadas'));
    }

    public function cambiarEstado(Request $request, Recurso $recurso)
    {
        abort_unless(auth()->user()->esAdminGeneral(), 403);

        $request->validate(['estado' => 'required|in:disponible,ocupado,mantenimiento']);
        $recurso->update(['estado' => $request->estado]);

        return response()->json([
            'success' => true,
            'estado'  => $recurso->estado,
            'label'   => ucfirst($recurso->estado),
        ]);
    }

    public function toggleReservas(Request $request)
    {
        abort_unless(auth()->user()->esAdminGeneral(), 403);

        $actual  = Configuracion::reservasHabilitadas();
        $nuevo   = !$actual;
        Configuracion::set('reservas_habilitadas', $nuevo ? '1' : '0');

        return response()->json([
            'success'   => true,
            'habilitado' => $nuevo,
            'mensaje'   => $nuevo ? 'Reservas habilitadas.' : 'Reservas bloqueadas.',
        ]);
    }
}
