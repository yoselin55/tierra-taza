<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promocion;
use Illuminate\Http\Request;

class PromocionAdminController extends Controller
{
    public function index()
    {
        $promociones = Promocion::withCount('productos')->latest()->get();
        return view('admin.promociones.index', compact('promociones'));
    }

    public function create()
    {
        return view('admin.promociones.form', ['promocion' => new Promocion]);
    }

    public function store(Request $request)
    {
        Promocion::create($this->validar($request));
        return redirect()->route('admin.promociones.index')->with('ok', 'Promoción creada.');
    }

    public function edit(Promocion $promocion)
    {
        return view('admin.promociones.form', compact('promocion'));
    }

    public function update(Request $request, Promocion $promocion)
    {
        $promocion->update($this->validar($request));
        return redirect()->route('admin.promociones.index')->with('ok', 'Promoción actualizada.');
    }

    public function destroy(Promocion $promocion)
    {
        $promocion->productos()->update(['promocion_id' => null, 'oferta_activa' => false]);
        $promocion->delete();
        return back()->with('ok', 'Promoción eliminada.');
    }

    public function toggleActiva(Promocion $promocion)
    {
        $promocion->update(['activa' => !$promocion->activa]);
        return response()->json(['activa' => $promocion->activa]);
    }

    private function validar(Request $request): array
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:120',
            'descripcion' => 'nullable|string|max:255',
            'color'       => 'nullable|string|max:7',
            'fecha_inicio'=> 'nullable|date',
            'fecha_fin'   => 'nullable|date|after_or_equal:fecha_inicio',
        ]);
        $data['activa'] = $request->boolean('activa');
        $data['color']  = $data['color'] ?? '#D4A84B';
        return $data;
    }
}
