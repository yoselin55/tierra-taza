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
            $nuevos = Pedido::where('estado', 'pendiente')
                ->where('created_at', '>', $since)
                ->latest()->take(5)->get();
            $result['pedidos'] = $nuevos->count();
            foreach ($nuevos as $p) {
                $result['items'][] = [
                    'type' => 'pedido',
                    'icon' => 'fire',
                    'msg'  => 'Nuevo pedido #'.$p->id.' — '.$p->nombre_cliente,
                    'time' => $p->created_at->format('H:i'),
                ];
            }

        } elseif ($user->esCoordinadorDelivery()) {
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
                    'msg'  => 'Pago pendiente S/'.$total.' — '.$p->metodo_pago,
                    'time' => $p->created_at->format('H:i'),
                ];
            }

        } else {
            // admin_general / admin_sistema
            $pedNuevos   = Pedido::where('estado', 'pendiente')->where('created_at', '>', $since)->latest()->take(5)->get();
            $resNuevas   = Reserva::where('created_at', '>', $since)->latest()->take(5)->get();
            $pagosNuevos = Pago::with('pedido')->where('estado', 'pendiente')->where('created_at', '>', $since)->latest()->take(5)->get();

            $result['pedidos']  = $pedNuevos->count();
            $result['reservas'] = $resNuevas->count();
            $result['pagos']    = $pagosNuevos->count();

            foreach ($pedNuevos as $p) {
                $result['items'][] = ['type' => 'pedido',  'icon' => 'bag',       'msg' => 'Pedido #'.$p->id.' de '.$p->nombre_cliente, 'time' => $p->created_at->format('H:i')];
            }
            foreach ($resNuevas as $r) {
                $result['items'][] = ['type' => 'reserva', 'icon' => 'calendar3', 'msg' => 'Nueva reserva de '.$r->nombre,              'time' => $r->created_at->format('H:i')];
            }
            foreach ($pagosNuevos as $p) {
                $total = $p->pedido ? number_format($p->pedido->total, 2) : '—';
                $result['items'][] = ['type' => 'pago', 'icon' => 'wallet2', 'msg' => 'Pago pendiente S/'.$total, 'time' => $p->created_at->format('H:i')];
            }
        }

        return response()->json($result);
    }
}
