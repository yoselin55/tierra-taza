<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Reserva;
use App\Models\Pago;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotifController extends Controller
{
    public function poll(Request $request)
    {
        try {
            $since = $request->input('since')
                ? Carbon::parse($request->input('since'))
                : now()->subMinutes(2);
        } catch (\Exception $e) {
            $since = now()->subMinutes(2);
        }

        $user   = auth()->user();
        $result = ['pedidos' => 0, 'reservas' => 0, 'pagos' => 0, 'items' => []];

        if ($user->esBarista()) {
            // Barista solo recibe notif cuando el cajero validó el pago (estado pasó a en_preparacion)
            $nuevos = Pedido::where('estado', 'en_preparacion')
                ->where('updated_at', '>', $since)
                ->latest('updated_at')->take(5)->get();
            $result['pedidos'] = $nuevos->count();
            foreach ($nuevos as $p) {
                $result['items'][] = [
                    'type' => 'pedido',
                    'icon' => 'fire',
                    'msg'  => 'Pedido #'.$p->id.' listo para preparar — '.$p->nombre_cliente,
                    'time' => $p->updated_at->format('H:i'),
                ];
            }

        } elseif ($user->esCoordinadorDelivery()) {
            // Coordinador recibe notif cuando barista marcó como listo
            $nuevos = Pedido::where('estado', 'listo')
                ->where('updated_at', '>', $since)
                ->latest('updated_at')->take(5)->get();
            $result['pedidos'] = $nuevos->count();
            foreach ($nuevos as $p) {
                $result['items'][] = [
                    'type' => 'pedido',
                    'icon' => 'truck',
                    'msg'  => 'Pedido #'.$p->id.' listo para delivery — '.$p->nombre_cliente,
                    'time' => $p->updated_at->format('H:i'),
                ];
            }

        } elseif ($user->esCajero()) {
            // Cajero recibe notif cuando llega un nuevo pedido con pago pendiente
            $nuevos = Pago::with('pedido')
                ->where('estado', 'pendiente')
                ->where('created_at', '>', $since)
                ->latest()->take(5)->get();
            $result['pagos'] = $nuevos->count();
            foreach ($nuevos as $p) {
                $total = $p->pedido ? number_format($p->pedido->total, 2) : '—';
                $result['items'][] = [
                    'type' => 'pago',
                    'icon' => 'wallet2',
                    'msg'  => 'Nuevo pago por validar S/'.$total.' — '.$p->metodo_pago,
                    'time' => $p->created_at->format('H:i'),
                ];
            }

        } else {
            // admin_general / admin_sistema — recibe TODO el flujo
            $pedNuevos     = Pedido::where('estado', 'pendiente')   ->where('created_at',  '>', $since)->latest()->take(5)->get();
            $pedPreparando = Pedido::where('estado', 'en_preparacion')->where('updated_at', '>', $since)->latest('updated_at')->take(5)->get();
            $pedListos     = Pedido::where('estado', 'listo')        ->where('updated_at', '>', $since)->latest('updated_at')->take(5)->get();
            $resNuevas     = Reserva::where('created_at', '>', $since)->latest()->take(5)->get();
            $pagosNuevos   = Pago::with('pedido')->where('estado', 'pendiente')->where('created_at', '>', $since)->latest()->take(5)->get();

            $result['pedidos']  = $pedNuevos->count() + $pedPreparando->count() + $pedListos->count();
            $result['reservas'] = $resNuevas->count();
            $result['pagos']    = $pagosNuevos->count();

            foreach ($pedNuevos as $p) {
                $result['items'][] = ['type' => 'pedido',  'icon' => 'bag',       'msg' => 'Nuevo pedido #'.$p->id.' de '.$p->nombre_cliente,         'time' => $p->created_at->format('H:i')];
            }
            foreach ($pagosNuevos as $p) {
                $total = $p->pedido ? number_format($p->pedido->total, 2) : '—';
                $result['items'][] = ['type' => 'pago',    'icon' => 'wallet2',   'msg' => 'Pago por validar S/'.$total.' — '.$p->metodo_pago,        'time' => $p->created_at->format('H:i')];
            }
            foreach ($pedPreparando as $p) {
                $result['items'][] = ['type' => 'pedido',  'icon' => 'fire',      'msg' => 'Pedido #'.$p->id.' en preparación — '.$p->nombre_cliente,  'time' => $p->updated_at->format('H:i')];
            }
            foreach ($pedListos as $p) {
                $result['items'][] = ['type' => 'pedido',  'icon' => 'truck',     'msg' => 'Pedido #'.$p->id.' listo para delivery — '.$p->nombre_cliente, 'time' => $p->updated_at->format('H:i')];
            }
            foreach ($resNuevas as $r) {
                $result['items'][] = ['type' => 'reserva', 'icon' => 'calendar3', 'msg' => 'Nueva reserva de '.$r->nombre,                              'time' => $r->created_at->format('H:i')];
            }
        }

        return response()->json($result);
    }
}
