<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Expand enum to include 'rechazado'
        DB::statement("ALTER TABLE pagos MODIFY COLUMN estado ENUM(
            'pendiente','completado','rechazado','fallido','reembolsado'
        ) NOT NULL DEFAULT 'pendiente'");

        Schema::table('pagos', function (Blueprint $table) {
            $table->timestamp('aprobado_en')->nullable()->after('notas_cajero');
        });
    }

    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropColumn('aprobado_en');
        });

        DB::statement("ALTER TABLE pagos MODIFY COLUMN estado ENUM(
            'pendiente','completado','fallido','reembolsado'
        ) NOT NULL DEFAULT 'pendiente'");
    }
};
