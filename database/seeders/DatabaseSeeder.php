<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Producto;
use App\Models\Recurso;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Usuarios ──────────────────────────────────────────────
        $usuarios = [
            ['nombre' => 'Admin General',       'email' => 'general@tierraytaza.pe',     'password' => Hash::make('General1234'),  'dni' => '67890123', 'rol' => 'admin_general'],
            ['nombre' => 'Barista José',         'email' => 'barista@tierraytaza.pe',     'password' => Hash::make('Barista1234'),  'dni' => '23456789', 'rol' => 'barista'],
            ['nombre' => 'Cajero María',         'email' => 'cajero@tierraytaza.pe',      'password' => Hash::make('Cajero1234'),   'dni' => '12345678', 'rol' => 'cajero'],
            ['nombre' => 'Coord. Delivery Luis', 'email' => 'delivery@tierraytaza.pe',    'password' => Hash::make('Delivery1234'), 'dni' => '34567890', 'rol' => 'coordinador_delivery'],
            ['nombre' => 'Admin Sistema Ana',    'email' => 'sistema@tierraytaza.pe',     'password' => Hash::make('Sistema1234'),  'dni' => '45678901', 'rol' => 'admin_sistema'],
            ['nombre' => 'Cliente Demo',         'email' => 'cliente@demo.pe',            'password' => Hash::make('Cliente1234'),  'dni' => '56789012', 'rol' => 'cliente'],
        ];

        foreach ($usuarios as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                ['nombre' => $u['nombre'], 'password' => $u['password'], 'dni' => $u['dni'], 'rol' => $u['rol']]
            );
        }

        // Actualizar roles de usuarios existentes con emails viejos
        User::where('email', 'admin@tierraytaza.pe')->update(['rol' => 'admin_sistema']);
        User::where('email', 'supervisor@tierraytaza.pe')->update(['rol' => 'cajero']);

        // ── Productos ─────────────────────────────────────────────
        $productos = [
            // Bebidas Calientes
            ['nombre' => 'Espresso Clásico',         'descripcion' => 'Café expreso concentrado con crema perfecta, origen Cajamarca.',          'precio' => 8.50,  'categoria' => 'calientes',  'stock' => 50, 'rating' => 4.8],
            ['nombre' => 'Cappuccino Artesanal',      'descripcion' => 'Espresso con leche vaporizada y espuma de leche. Arte latte incluido.',    'precio' => 12.00, 'categoria' => 'calientes',  'stock' => 40, 'rating' => 4.7],
            ['nombre' => 'Latte de Vainilla',         'descripcion' => 'Espresso suave con leche caliente y toque de vainilla natural.',           'precio' => 13.50, 'categoria' => 'calientes',  'stock' => 35, 'rating' => 4.6],
            ['nombre' => 'Café Americano',            'descripcion' => 'Espresso diluido en agua caliente, suave y aromático.',                    'precio' => 7.00,  'categoria' => 'calientes',  'stock' => 60, 'rating' => 4.5],
            // Bebidas Frías
            ['nombre' => 'Cold Brew 24h',             'descripcion' => 'Café extraído en frío durante 24 horas. Suave y concentrado.',             'precio' => 15.00, 'categoria' => 'frias',      'stock' => 25, 'rating' => 4.9],
            ['nombre' => 'Frappé de Caramelo',        'descripcion' => 'Café frío batido con caramelo, leche y crema batida.',                     'precio' => 16.00, 'categoria' => 'frias',      'stock' => 30, 'rating' => 4.7],
            ['nombre' => 'Matcha Latte Frío',         'descripcion' => 'Té matcha ceremonial con leche de avena y hielo.',                         'precio' => 14.00, 'categoria' => 'frias',      'stock' => 20, 'rating' => 4.6],
            ['nombre' => 'Limonada de Maracuyá',      'descripcion' => 'Bebida refrescante de maracuyá fresco con hierbabuena.',                   'precio' => 10.00, 'categoria' => 'frias',      'stock' => 40, 'rating' => 4.5],
            // Postres
            ['nombre' => 'Brownie de Café',           'descripcion' => 'Brownie húmedo con chips de chocolate y esencia de café peruano.',         'precio' => 9.00,  'categoria' => 'postres',    'stock' => 20, 'rating' => 4.8],
            ['nombre' => 'Cheesecake de Maracuyá',    'descripcion' => 'Cheesecake cremoso con coulis de maracuyá fresco.',                        'precio' => 11.00, 'categoria' => 'postres',    'stock' => 15, 'rating' => 4.9],
            ['nombre' => 'Alfajor de Manjar',         'descripcion' => 'Alfajor artesanal con manjar blanco y coco rallado.',                      'precio' => 5.00,  'categoria' => 'postres',    'stock' => 30, 'rating' => 4.6],
            ['nombre' => 'Tiramisú de la Casa',       'descripcion' => 'Tiramisú clásico con café espresso y mascarpone importado.',               'precio' => 13.00, 'categoria' => 'postres',    'stock' => 12, 'rating' => 4.8],
            // Café en Grano
            ['nombre' => 'Blend Tierra y Taza 250g',  'descripcion' => 'Mezcla exclusiva de granos de Cajamarca y San Martín. Tostado medio.',    'precio' => 28.00, 'categoria' => 'cafe_grano', 'stock' => 20, 'rating' => 4.9],
            ['nombre' => 'Café Orgánico Selva 500g',  'descripcion' => 'Café de altura, certificado orgánico, origen Chanchamayo.',               'precio' => 45.00, 'categoria' => 'cafe_grano', 'stock' => 15, 'rating' => 4.8],
            ['nombre' => 'Tostado Oscuro Premium 1kg','descripcion' => 'Grano de tostado oscuro para espresso potente, mezcla de valles peruanos.','precio' => 75.00, 'categoria' => 'cafe_grano', 'stock' => 10, 'rating' => 4.7],
        ];

        foreach ($productos as $p) {
            Producto::firstOrCreate(['nombre' => $p['nombre']], $p);
        }

        // ── Recursos (Mesas y Coworking) ──────────────────────────
        $recursos = [
            ['tipo' => 'mesa', 'numero' => 1,  'capacidad' => 2, 'estado' => 'disponible', 'pos_x' => 0, 'pos_y' => 0],
            ['tipo' => 'mesa', 'numero' => 2,  'capacidad' => 4, 'estado' => 'disponible', 'pos_x' => 1, 'pos_y' => 0],
            ['tipo' => 'mesa', 'numero' => 3,  'capacidad' => 4, 'estado' => 'ocupado',    'pos_x' => 2, 'pos_y' => 0],
            ['tipo' => 'mesa', 'numero' => 4,  'capacidad' => 2, 'estado' => 'disponible', 'pos_x' => 3, 'pos_y' => 0],
            ['tipo' => 'mesa', 'numero' => 5,  'capacidad' => 6, 'estado' => 'disponible', 'pos_x' => 0, 'pos_y' => 1],
            ['tipo' => 'mesa', 'numero' => 6,  'capacidad' => 4, 'estado' => 'disponible', 'pos_x' => 1, 'pos_y' => 1],
            ['tipo' => 'mesa', 'numero' => 7,  'capacidad' => 2, 'estado' => 'ocupado',    'pos_x' => 2, 'pos_y' => 1],
            ['tipo' => 'mesa', 'numero' => 8,  'capacidad' => 4, 'estado' => 'disponible', 'pos_x' => 3, 'pos_y' => 1],
            ['tipo' => 'coworking', 'numero' => 1, 'capacidad' => 1, 'estado' => 'disponible', 'pos_x' => 0, 'pos_y' => 3],
            ['tipo' => 'coworking', 'numero' => 2, 'capacidad' => 1, 'estado' => 'disponible', 'pos_x' => 1, 'pos_y' => 3],
            ['tipo' => 'coworking', 'numero' => 3, 'capacidad' => 2, 'estado' => 'ocupado',    'pos_x' => 2, 'pos_y' => 3],
            ['tipo' => 'coworking', 'numero' => 4, 'capacidad' => 1, 'estado' => 'disponible', 'pos_x' => 3, 'pos_y' => 3],
            ['tipo' => 'coworking', 'numero' => 5, 'capacidad' => 2, 'estado' => 'disponible', 'pos_x' => 0, 'pos_y' => 4],
            ['tipo' => 'coworking', 'numero' => 6, 'capacidad' => 1, 'estado' => 'disponible', 'pos_x' => 1, 'pos_y' => 4],
        ];

        foreach ($recursos as $r) {
            Recurso::firstOrCreate(
                ['tipo' => $r['tipo'], 'numero' => $r['numero']],
                $r
            );
        }

        $this->command->info('Seeder completado.');
        $this->command->table(
            ['Rol', 'Email', 'Contraseña'],
            [
                ['Admin General',       'general@tierraytaza.pe',  'General1234'],
                ['Barista / Cocinero',  'barista@tierraytaza.pe',  'Barista1234'],
                ['Cajero',              'cajero@tierraytaza.pe',   'Cajero1234'],
                ['Coord. Delivery',     'delivery@tierraytaza.pe', 'Delivery1234'],
                ['Admin del Sistema',   'sistema@tierraytaza.pe',  'Sistema1234'],
                ['Cliente',             'cliente@demo.pe',         'Cliente1234'],
            ]
        );
    }
}
