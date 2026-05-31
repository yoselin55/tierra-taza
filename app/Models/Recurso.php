<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recurso extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo', 'numero', 'capacidad', 'estado', 'pos_x', 'pos_y',
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    public function estaDisponible(): bool
    {
        return $this->estado === 'disponible';
    }

    public function getTipoLabelAttribute(): string
    {
        return match($this->tipo) {
            'mesa'       => 'Mesa',
            'coworking'  => 'Coworking',
            default      => ucfirst($this->tipo),
        };
    }

    public function getIconoAttribute(): string
    {
        return match($this->tipo) {
            'mesa'       => '🪑',
            'coworking'  => '💻',
            default      => '📍',
        };
    }
}
