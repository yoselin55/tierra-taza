<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->json('datos_pago')->nullable()->after('referencia');
            // El estado ahora puede iniciar como pendiente y el cajero lo valida
            $table->string('notas_cajero')->nullable()->after('datos_pago');
        });
    }

    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropColumn(['datos_pago', 'notas_cajero']);
        });
    }
};