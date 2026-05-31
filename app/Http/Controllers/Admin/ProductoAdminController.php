<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::query();

        if ($request->categoria) {
            $query->where('categoria', $request->categoria);
        }
        if ($request->busqueda) {
            $query->where('nombre', 'like', '%'.$request->busqueda.'%');
        }

        $productos = $query->orderBy('categoria')->orderBy('nombre')->paginate(15);

        return view('admin.productos.index', compact('productos'));
    }

    public function create()
    {
        return view('admin.productos.form', ['producto' => new Producto()]);
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        Producto::create($data);

        return redirect()->route('admin.productos.index')->with('success', 'Producto creado exitosamente.');
    }

    public function edit(Producto $producto)
    {
        return view('admin.productos.form', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $data = $this->validar($request, $producto->id);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($data);

        return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(Producto $producto)
    {
        $producto->update(['estado' => false]);
        return redirect()->route('admin.productos.index')->with('success', 'Producto desactivado.');
    }

    private function validar(Request $request, $id = null): array
    {
        return $request->validate([
            'nombre'      => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'precio'      => 'required|numeric|min:0',
            'categoria'   => 'required|in:calientes,frias,postres,cafe_grano',
            'stock'       => 'required|integer|min:0',
            'estado'      => 'boolean',
            'imagen'      => 'nullable|image|max:2048',
        ]);
    }
}
