<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('direccion_envio')->nullable()->after('notas');
            $table->string('referencia_envio')->nullable()->after('direccion_envio');
            $table->date('fecha_entrega')->nullable()->after('referencia_envio');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn(['direccion_envio', 'referencia_envio', 'fecha_entrega']);
        });
    }
};
