<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    protected $table = 'promociones';

    protected $fillable = ['nombre', 'descripcion', 'color', 'activa', 'fecha_inicio', 'fecha_fin'];

    protected $casts = [
        'activa'       => 'boolean',
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function estaVigente(): bool
    {
        if (!$this->activa) return false;
        $hoy = now()->startOfDay();
        if ($this->fecha_inicio && $this->fecha_inicio->gt($hoy)) return false;
        if ($this->fecha_fin    && $this->fecha_fin->lt($hoy))    return false;
        return true;
    }

    public function getProductosActivosAttribute()
    {
        return $this->productos()->activos()->where('oferta_activa', true)->get();
    }
}
