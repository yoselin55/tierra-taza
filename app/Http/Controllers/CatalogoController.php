<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Resena;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        $categoria = $request->get('categoria', 'todos');
        $busqueda  = $request->get('busqueda');
        $orden     = $request->get('orden', 'rating');

        $query = Producto::activos();

        if ($categoria && $categoria !== 'todos') {
            $query->where('categoria', $categoria);
        }

        if ($busqueda) {
            $query->where('nombre', 'like', "%{$busqueda}%");
        }

        match($orden) {
            'precio_asc'  => $query->orderBy('precio', 'asc'),
            'precio_desc' => $query->orderBy('precio', 'desc'),
            'nombre'      => $query->orderBy('nombre', 'asc'),
            default       => $query->orderByDesc('rating'),
        };

        $productos = $query->paginate(12)->withQueryString();

        $categorias = [
            'todos'      => 'Todos',
            'calientes'  => 'Bebidas Calientes',
            'frias'      => 'Bebidas Frías',
            'postres'    => 'Postres',
            'cafe_grano' => 'Café en Grano',
        ];

        return view('shop.catalogo', compact('productos', 'categorias', 'categoria', 'busqueda', 'orden'));
    }

    public function show(Producto $producto)
    {
        $resenas = $producto->resenas()->with('user')->latest()->get();
        $yaCompro = false;

        if (auth()->check()) {
            // Verificar si el usuario ya compró este producto
            $yaCompro = auth()->user()->pedidos()
                ->whereHas('detalles', fn($q) => $q->where('producto_id', $producto->id))
                ->where('estado', 'entregado')
                ->exists();
        }

        $yaPusoResena = false;
        if (auth()->check()) {
            $yaPusoResena = Resena::where('user_id', auth()->id())
                ->where('producto_id', $producto->id)
                ->exists();
        }

        $relacionados = Producto::activos()
            ->where('categoria', $producto->categoria)
            ->where('id', '!=', $producto->id)
            ->take(4)->get();

        return view('shop.producto', compact('producto', 'resenas', 'yaCompro', 'yaPusoResena', 'relacionados'));
    }

    public function guardarResena(Request $request, Producto $producto)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario'   => 'nullable|string|max:500',
        ]);

        Resena::create([
            'user_id'      => auth()->id(),
            'producto_id'  => $producto->id,
            'calificacion' => $request->calificacion,
            'comentario'   => $request->comentario,
            'fecha'        => now(),
        ]);

        $producto->actualizarRating();

        return back()->with('success', '¡Gracias por tu reseña!');
    }
}
