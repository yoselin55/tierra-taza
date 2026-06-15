<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'descripcion', 'precio', 'categoria',
        'imagen', 'stock', 'estado', 'rating',
        'oferta_activa', 'nombre_oferta', 'precio_oferta', 'oferta_hasta',
    ];

    protected $casts = [
        'precio'        => 'decimal:2',
        'precio_oferta' => 'decimal:2',
        'rating'        => 'decimal:1',
        'estado'        => 'boolean',
        'oferta_activa' => 'boolean',
        'oferta_hasta'  => 'date',
    ];

    // Relaciones
    public function detallesPedido()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function resenas()
    {
        return $this->hasMany(Resena::class);
    }

    // Helpers
    public function getCategoriaLabelAttribute(): string
    {
        return match($this->categoria) {
            'calientes'   => 'Bebidas Calientes',
            'frias'       => 'Bebidas Frías',
            'postres'     => 'Postres',
            'cafe_grano'  => 'Café en Grano',
            default       => ucfirst($this->categoria),
        };
    }

    public function getImagenUrlAttribute(): string
    {
        // ✅ CORREGIDO: busca en storage/ donde el admin guarda las imágenes
        if ($this->imagen) {
            return asset('storage/' . $this->imagen);
        }

        // Fallback Unsplash si el producto no tiene imagen propia
        return match($this->categoria) {
            'calientes'  => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=400&h=300&fit=crop',
            'frias'      => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=400&h=300&fit=crop',
            'postres'    => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=400&h=300&fit=crop',
            'cafe_grano' => 'https://images.unsplash.com/photo-1611854779393-1b2da9d400fe?w=400&h=300&fit=crop',
            default      => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=400&h=300&fit=crop',
        };
    }

    public function hayStock(): bool
    {
        return $this->stock > 0 && $this->estado;
    }

    public function estaEnOferta(): bool
    {
        return $this->oferta_activa
            && $this->precio_oferta !== null
            && ($this->oferta_hasta === null || $this->oferta_hasta->greaterThanOrEqualTo(now()->startOfDay()));
    }

    public function getPrecioFinalAttribute(): string
    {
        return $this->estaEnOferta() ? $this->precio_oferta : $this->precio;
    }

    public function actualizarRating(): void
    {
        $promedio = $this->resenas()->avg('calificacion');
        $this->update(['rating' => $promedio ?? 0]);
    }

    // Scope filtros
    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }

    public function scopeCategoria($query, $categoria)
    {
        if ($categoria && $categoria !== 'todos') {
            return $query->where('categoria', $categoria);
        }
        return $query;
    }
}