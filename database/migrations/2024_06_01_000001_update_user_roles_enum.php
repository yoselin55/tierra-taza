<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ampliar el enum para incluir valores viejos Y nuevos simultáneamente
        DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM(
            'cliente',
            'admin_catalogo',
            'barista',
            'cajero',
            'coordinador_delivery',
            'delivery',
            'supervisor',
            'admin_sistema',
            'admin_general'
        ) NOT NULL DEFAULT 'cliente'");

        // 2. Migrar datos existentes al nuevo nombre de rol
        DB::statement("UPDATE users SET rol = 'admin_sistema'        WHERE rol = 'admin_catalogo'");
        DB::statement("UPDATE users SET rol = 'coordinador_delivery' WHERE rol = 'delivery'");
        DB::statement("UPDATE users SET rol = 'cajero'               WHERE rol = 'supervisor'");

        // 3. Reducir el enum a solo los valores nuevos
        DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM(
            'cliente',
            'barista',
            'cajero',
            'coordinador_delivery',
            'admin_sistema',
            'admin_general'
        ) NOT NULL DEFAULT 'cliente'");
    }

    public function down(): void
    {
        // Revertir al enum anterior
        DB::statement("UPDATE users SET rol = 'admin_catalogo' WHERE rol = 'admin_sistema'");
        DB::statement("UPDATE users SET rol = 'delivery'       WHERE rol = 'coordinador_delivery'");
        DB::statement("UPDATE users SET rol = 'supervisor'     WHERE rol = 'cajero'");

        DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM(
            'cliente',
            'admin_catalogo',
            'barista',
            'delivery',
            'supervisor',
            'admin_general'
        ) NOT NULL DEFAULT 'cliente'");
    }
};
