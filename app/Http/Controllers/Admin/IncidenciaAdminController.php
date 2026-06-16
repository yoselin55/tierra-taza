<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Incidencia;
use Illuminate\Http\Request;

class IncidenciaAdminController extends Controller
{
    public function index(Request $request)
    {
        $filtro = $request->estado;

        $estadosResueltos = ['validada', 'rechazada', 'resuelta'];

        if ($filtro) {
            $incidencias = Incidencia::with(['pedido.user'])
                ->where('estado', $filtro)
                ->latest()
                ->paginate(20);
            return view('admin.incidencias.index', compact('incidencias', 'filtro'));
        }

        $activas   = Incidencia::with(['pedido.user'])
            ->whereIn('estado', ['abierta', 'en_proceso'])
            ->latest()
            ->get();

        $resueltas = Incidencia::with(['pedido.user'])
            ->whereIn('estado', ['validada', 'rechazada', 'resuelta'])
            ->latest()
            ->paginate(15);

        return view('admin.incidencias.index', [
            'incidencias' => null,
            'activas'     => $activas,
            'resueltas'   => $resueltas,
            'filtro'      => null,
        ]);
    }

    public function responder(Request $request, Incidencia $incidencia)
    {
        $request->validate([
            'respuesta' => 'required|string|max:500',
            'estado'    => 'required|in:en_proceso,validada,rechazada',
        ]);

        $incidencia->update([
            'respuesta' => $request->respuesta,
            'estado'    => $request->estado,
        ]);

        return back()->with('success', 'Respuesta enviada al cliente.');
    }
}