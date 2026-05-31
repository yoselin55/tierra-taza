<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'fecha', 'estado', 'total',
        'metodo_pago', 'nombre_cliente', 'dni_cliente', 'notas',
        'direccion_envio', 'referencia_envio', 'fecha_entrega',
    ];

    protected $casts = [
        'fecha'         => 'datetime',
        'total'         => 'decimal:2',
        'fecha_entrega' => 'date',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function pago()
    {
        return $this->hasOne(Pago::class);
    }

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class);
    }

    // Helpers
    // Estados que maneja el Barista
    public static array $estadosBarista = ['en_preparacion', 'casi_listo', 'listo'];
    // Estados que maneja el Coordinador Delivery
    public static array $estadosDelivery = ['recogido', 'en_camino', 'cerca_destino', 'entregado'];
    // Estados visibles por Barista
    public static array $visiblesBarista = ['pendiente', 'en_preparacion', 'casi_listo', 'listo'];
    // Estados visibles por Delivery
    public static array $visiblesDelivery = ['listo', 'recogido', 'en_camino', 'cerca_destino', 'entregado'];

    public function getEstadoLabelAttribute(): string
    {
        return match($this->estado) {
            'pendiente'      => 'Pendiente pago',
            'en_preparacion' => 'En preparación',
            'casi_listo'     => 'Casi listo',
            'listo'          => 'Listo para despacho',
            'recogido'       => 'Recogido por delivery',
            'en_camino'      => 'En camino',
            'cerca_destino'  => 'Cerca al destino',
            'entregado'      => 'Entregado',
            'cancelado'      => 'Cancelado',
            default          => ucfirst($this->estado),
        };
    }

    public function getEstadoBadgeAttribute(): string
    {
        return match($this->estado) {
            'pendiente'      => 'badge-warning',
            'en_preparacion' => 'badge-info',
            'casi_listo'     => 'badge-purple',
            'listo'          => 'badge-success',
            'recogido'       => 'badge-info',
            'en_camino'      => 'badge-info',
            'cerca_destino'  => 'badge-warning',
            'entregado'      => 'badge-success',
            'cancelado'      => 'badge-danger',
            default          => 'badge-warning',
        };
    }

    public function getEstadoPasoAttribute(): int
    {
        return match($this->estado) {
            'pendiente'      => 1,
            'en_preparacion' => 2,
            'casi_listo'     => 3,
            'listo'          => 4,
            'recogido'       => 5,
            'en_camino'      => 6,
            'cerca_destino'  => 7,
            'entregado'      => 8,
            default          => 0,
        };
    }

    public function getMetodoPagoLabelAttribute(): string
    {
        return match($this->metodo_pago) {
            'yape'           => 'Yape',
            'plin'           => 'Plin',
            'tarjeta'        => 'Tarjeta de Crédito/Débito',
            'efectivo'       => 'Efectivo',
            'transferencia'  => 'Transferencia Bancaria',
            default          => ucfirst($this->metodo_pago ?? ''),
        };
    }

    public function estaEntregado(): bool
    {
        return $this->estado === 'entregado';
    }
}
