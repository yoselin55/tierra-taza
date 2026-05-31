<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\Recurso;
use App\Models\Reserva;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function index()
    {
        $recursos = Recurso::orderBy('tipo')->orderBy('numero')->get();

        $mesas      = $recursos->where('tipo', 'mesa');
        $coworkings = $recursos->where('tipo', 'coworking');

        return view('reservas.index', compact('recursos', 'mesas', 'coworkings'));
    }

    public function store(Request $request)
    {
        if (!Configuracion::reservasHabilitadas()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Las reservas están temporalmente deshabilitadas.'], 422);
            }
            return back()->with('error', 'Las reservas están temporalmente deshabilitadas.');
        }

        $request->validate([
            'recurso_id'  => 'required|exists:recursos,id',
            'nombre'      => 'required|string|max:100',
            'dni'         => 'required|digits:8',
            'fecha'       => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required',
            'duracion'    => 'required|in:1h,4h,dia',
            'personas'    => 'nullable|integer|min:1|max:20',
        ], [
            'recurso_id.required' => 'Selecciona una mesa o coworking.',
            'nombre.required'     => 'El nombre es obligatorio.',
            'dni.required'        => 'El DNI es obligatorio.',
            'dni.digits'          => 'El DNI debe tener 8 dígitos.',
            'fecha.required'      => 'La fecha es obligatoria.',
            'fecha.after_or_equal'=> 'La fecha debe ser hoy o posterior.',
            'hora_inicio.required'=> 'La hora es obligatoria.',
        ]);

        $recurso = Recurso::findOrFail($request->recurso_id);

        if (!$recurso->estaDisponible()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Este espacio ya no está disponible.'], 422);
            }
            return back()->with('error', 'Este espacio ya no está disponible.');
        }

        // Crear reserva
        $reserva = Reserva::create([
            'user_id'     => auth()->id(),
            'recurso_id'  => $recurso->id,
            'nombre'      => $request->nombre,
            'dni'         => $request->dni,
            'fecha'       => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'duracion'    => $request->duracion,
            'personas'    => $request->personas ?? 1,
            'estado'      => 'confirmada',
        ]);

        // Marcar recurso como ocupado
        $recurso->update(['estado' => 'ocupado']);

        if ($request->expectsJson()) {
            return response()->json([
                'success'   => true,
                'message'   => "¡Reserva confirmada! {$recurso->tipo_label} #{$recurso->numero} reservada para el {$request->fecha}.",
                'reserva'   => $reserva,
                'recurso_id'=> $recurso->id,
            ]);
        }

        return redirect()->route('reservas.index')->with('success', 'Reserva realizada con éxito.');
    }

    public function estadoRecursos()
    {
        Reserva::liberarVencidas();
        $recursos = Recurso::select('id', 'tipo', 'numero', 'estado', 'capacidad')->get();
        return response()->json($recursos);
    }
}
