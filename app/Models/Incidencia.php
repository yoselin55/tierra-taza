<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id', 'tipo', 'descripcion', 'imagen', 'fecha', 'estado', 'respuesta',
    ];

    public function getImagenUrlAttribute(): ?string
    {
        return $this->imagen ? asset('storage/' . $this->imagen) : null;
    }

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function getTipoLabelAttribute(): string
    {
        return match($this->tipo) {
            'problema'   => 'Problema con el pedido',
            'devolucion' => 'Solicitud de devolución',
            'reenvio'    => 'Solicitud de reenvío',
            default      => ucfirst($this->tipo),
        };
    }

    public function getTipoIconoAttribute(): string
    {
        return match($this->tipo) {
            'problema'   => 'bi-exclamation-triangle-fill',
            'devolucion' => 'bi-arrow-counterclockwise',
            'reenvio'    => 'bi-send-fill',
            default      => 'bi-question-circle',
        };
    }

    public function getEstadoBadgeAttribute(): string
    {
        return match($this->estado) {
            'abierta'    => 'badge-danger',
            'en_proceso' => 'badge-warning',
            'validada'   => 'badge-success',
            'rechazada'  => 'badge-danger',
            'resuelta'   => 'badge-success',
            default      => 'badge-info',
        };
    }

    public function getEstadoLabelAttribute(): string
    {
        return match($this->estado) {
            'abierta'    => 'Abierta',
            'en_proceso' => 'En proceso',
            'validada'   => 'Validada',
            'rechazada'  => 'Rechazada',
            'resuelta'   => 'Resuelta',
            default      => ucfirst($this->estado),
        };
    }

    public function estaResuelta(): bool
    {
        return in_array($this->estado, ['validada', 'rechazada', 'resuelta']);
    }
}