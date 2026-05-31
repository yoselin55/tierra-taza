<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{
    use HasFactory;

    protected $table = 'resenas';

    protected $fillable = [
        'user_id', 'producto_id', 'calificacion', 'comentario', 'fecha',
    ];

    protected $casts = [
        'fecha'        => 'datetime',
        'calificacion' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function getEstrellasFillAttribute(): array
    {
        $llenas = $this->calificacion;
        $vacias = 5 - $llenas;
        return ['llenas' => $llenas, 'vacias' => $vacias];
    }
}
