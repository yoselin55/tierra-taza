<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Incidencia;
use Illuminate\Http\Request;

class IncidenciaAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Incidencia::with(['pedido.user'])->latest();

        if ($request->estado) {
            $query->where('estado', $request->estado);
        }

        $incidencias = $query->paginate(20);

        return view('admin.incidencias.index', compact('incidencias'));
    }

    public function responder(Request $request, Incidencia $incidencia)
    {
        $request->validate([
            'respuesta'    => 'required|string|max:500',
            'estado'       => 'required|in:en_proceso,resuelta',
        ]);

        $incidencia->update([
            'respuesta' => $request->respuesta,
            'estado'    => $request->estado,
        ]);

        return back()->with('success', 'Respuesta enviada al cliente.');
    }
}