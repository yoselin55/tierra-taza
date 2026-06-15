<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->boolean('oferta_activa')->default(false)->after('estado');
            $table->string('nombre_oferta')->nullable()->after('oferta_activa');
            $table->decimal('precio_oferta', 8, 2)->nullable()->after('nombre_oferta');
            $table->date('oferta_hasta')->nullable()->after('precio_oferta');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['oferta_activa', 'nombre_oferta', 'precio_oferta', 'oferta_hasta']);
        });
    }
};
