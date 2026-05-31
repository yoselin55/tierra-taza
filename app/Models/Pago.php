<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id', 'metodo_pago', 'estado', 'fecha',
        'referencia', 'datos_pago', 'notas_cajero', 'aprobado_en',
    ];

    protected $casts = [
        'fecha'       => 'datetime',
        'aprobado_en' => 'datetime',
        'datos_pago'  => 'array',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function getEstadoLabelAttribute(): string
    {
        return match($this->estado) {
            'pendiente'   => 'Pendiente de validación',
            'completado'  => 'Validado',
            'rechazado'   => 'Rechazado',
            'reembolsado' => 'Reembolsado',
            default       => ucfirst($this->estado),
        };
    }

    public function getEstadoBadgeAttribute(): string
    {
        return match($this->estado) {
            'pendiente'   => 'badge-warning',
            'completado'  => 'badge-success',
            'rechazado'   => 'badge-danger',
            'reembolsado' => 'badge-info',
            default       => 'badge-warning',
        };
    }
}