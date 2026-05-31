<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nombre', 'email', 'password', 'dni', 'foto',
    ];

    // 'rol' se asigna solo internamente, nunca desde $request->all()
    protected $guarded = ['rol'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relaciones
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    public function resenas()
    {
        return $this->hasMany(Resena::class);
    }

    // ── Helpers de rol ────────────────────────────────────────────────

    public function esCliente(): bool
    {
        return $this->rol === 'cliente';
    }

    public function esAdmin(): bool
    {
        return in_array($this->rol, [
            'barista', 'cajero', 'coordinador_delivery', 'admin_sistema', 'admin_general',
        ]);
    }

    public function esAdminGeneral(): bool
    {
        return $this->rol === 'admin_general';
    }

    public function esBarista(): bool
    {
        return $this->rol === 'barista';
    }

    public function esCajero(): bool
    {
        return $this->rol === 'cajero';
    }

    public function esCoordinadorDelivery(): bool
    {
        return $this->rol === 'coordinador_delivery';
    }

    public function esAdminSistema(): bool
    {
        return $this->rol === 'admin_sistema';
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        $inicial = mb_strtoupper(mb_substr($this->nombre, 0, 1));
        return "https://ui-avatars.com/api/?name={$inicial}&background=C8963C&color=fff&size=128&font-size=0.5&bold=true";
    }

    public function getRolLabelAttribute(): string
    {
        return match($this->rol) {
            'barista'              => 'Barista / Cocinero',
            'cajero'               => 'Cajero',
            'coordinador_delivery' => 'Coord. Delivery',
            'admin_sistema'        => 'Admin del Sistema',
            'admin_general'        => 'Admin General',
            default                => 'Cliente',
        };
    }
}
