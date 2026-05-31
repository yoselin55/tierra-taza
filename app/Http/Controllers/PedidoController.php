<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Pago;
use App\Models\Producto;
use App\Models\Incidencia;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function checkout()
    {
        $carrito = session('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('carrito')->with('error', 'Tu carrito está vacío.');
        }

        $total = array_sum(array_map(fn($item) => $item['precio'] * $item['cantidad'], $carrito));

        return view('pedidos.checkout', compact('carrito', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_cliente'  => 'required|string|max:100',
            'dni_cliente'     => 'required|digits:8',
            'metodo_pago'     => 'required|in:yape,plin,tarjeta,efectivo,transferencia',
            'direccion_envio' => 'required|string|max:255',
            'fecha_entrega'   => 'required|date|after_or_equal:today',
        ], [
            'nombre_cliente.required'  => 'El nombre es obligatorio.',
            'dni_cliente.required'     => 'El DNI es obligatorio.',
            'dni_cliente.digits'       => 'El DNI debe tener 8 dígitos.',
            'metodo_pago.required'     => 'Selecciona un método de pago.',
            'direccion_envio.required' => 'La dirección de envío es obligatoria.',
            'fecha_entrega.required'   => 'La fecha de entrega es obligatoria.',
            'fecha_entrega.after_or_equal' => 'La fecha de entrega no puede ser en el pasado.',
        ]);

        $carrito = session('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('carrito')->with('error', 'Tu carrito está vacío.');
        }

        // Precios siempre desde la BD, nunca de la sesión (evita manipulación)
        $ids = array_column($carrito, 'id');
        $productosDB = Producto::whereIn('id', $ids)->where('estado', true)->get()->keyBy('id');

        $total = 0;
        foreach ($carrito as $item) {
            $prod = $productosDB->get($item['id']);
            if (!$prod) {
                return redirect()->route('carrito')->with('error', 'Un producto ya no está disponible.');
            }
            $total += $prod->precio * $item['cantidad'];
        }

        // Crear pedido
        $pedido = Pedido::create([
            'user_id'          => auth()->id(),
            'fecha'            => now(),
            'estado'           => 'pendiente',
            'total'            => $total,
            'metodo_pago'      => $request->metodo_pago,
            'nombre_cliente'   => $request->nombre_cliente,
            'dni_cliente'      => $request->dni_cliente,
            'notas'            => $request->notas,
            'direccion_envio'  => $request->direccion_envio,
            'referencia_envio' => $request->referencia_envio,
            'fecha_entrega'    => $request->fecha_entrega,
        ]);

        // Crear detalles con precio oficial de BD y actualizar stock
        foreach ($carrito as $item) {
            $prod = $productosDB->get($item['id']);
            DetallePedido::create([
                'pedido_id'   => $pedido->id,
                'producto_id' => $prod->id,
                'cantidad'    => $item['cantidad'],
                'precio'      => $prod->precio,
            ]);

            Producto::where('id', $prod->id)->decrement('stock', $item['cantidad']);
        }

        // Preparar datos de pago ingresados por el cliente
        $datosPago = $this->parsearDatosPago($request->metodo_pago, $request->datos_pago ?? []);

        // El efectivo no necesita validación: se confirma al entregar
        $estadoPago = $request->metodo_pago === 'efectivo' ? 'completado' : 'pendiente';

        Pago::create([
            'pedido_id'   => $pedido->id,
            'metodo_pago' => $request->metodo_pago,
            'estado'      => $estadoPago,
            'fecha'       => now(),
            'referencia'  => strtoupper(uniqid('TT-')),
            'datos_pago'  => $datosPago,
        ]);

        session()->forget('carrito');

        return redirect()->route('pedidos.boleta', $pedido->id)
            ->with('success', '¡Pedido realizado! ' . ($estadoPago === 'pendiente' ? 'El cajero validará tu pago pronto.' : 'Pago registrado.'));
    }

    private function parsearDatosPago(string $metodo, array $raw): array
    {
        return match ($metodo) {
            'yape', 'plin' => [
                'numero_celular' => preg_replace('/\D/', '', $raw['numero_celular'] ?? ''),
                'titular'        => trim($raw['titular'] ?? ''),
            ],
            'tarjeta' => [
                'titular'    => strtoupper(trim($raw['titular'] ?? '')),
                'ultimos4'   => preg_replace('/\D/', '', $raw['ultimos4'] ?? ''),
                'vencimiento'=> trim($raw['vencimiento'] ?? ''),
            ],
            'transferencia' => [
                'banco'         => trim($raw['banco'] ?? ''),
                'numero_cuenta' => trim($raw['numero_cuenta'] ?? ''),
                'titular'       => trim($raw['titular'] ?? ''),
            ],
            default => [],
        };
    }

    public function misPedidos()
    {
        $pedidos = auth()->user()->pedidos()
            ->with(['detalles.producto', 'pago'])
            ->latest()
            ->paginate(10);

        return view('pedidos.mis_pedidos', compact('pedidos'));
    }

    public function detalle(Pedido $pedido)
    {
        if ($pedido->user_id !== auth()->id()) {
            abort(403);
        }
        $pedido->load(['detalles.producto', 'pago', 'user', 'incidencias']);
        return view('pedidos.detalle', compact('pedido'));
    }

    public function boleta(Pedido $pedido)
    {
        if ($pedido->user_id !== auth()->id()) {
            abort(403);
        }
        $pedido->load(['detalles.producto', 'pago', 'user']);
        return view('pedidos.boleta', compact('pedido'));
    }

    public function comprobante(Pedido $pedido)
    {
        if ($pedido->user_id !== auth()->id()) {
            abort(403);
        }
        $pago = $pedido->pago;
        abort_unless($pago && $pago->estado === 'completado', 404, 'Comprobante no disponible.');
        $pago->load(['pedido.user', 'pedido.detalles.producto']);
        return view('comprobante', compact('pago'));
    }

    public function reportarIncidencia(Request $request, Pedido $pedido)
    {
        if ($pedido->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'tipo'        => 'required|in:problema,devolucion,reenvio',
            'descripcion' => 'required|string|max:500',
        ]);

        // Solo puede reportar si el pedido no es pendiente sin pagar
        Incidencia::create([
            'pedido_id'   => $pedido->id,
            'tipo'        => $request->tipo,
            'descripcion' => $request->descripcion,
            'fecha'       => now(),
            'estado'      => 'abierta',
        ]);

        return back()->with('success', 'Tu reclamo fue enviado. El cajero lo revisará y te responderá pronto.');
    }
}