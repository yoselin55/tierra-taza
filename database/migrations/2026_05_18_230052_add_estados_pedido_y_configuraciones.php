<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pedidos MODIFY COLUMN estado ENUM(
            'pendiente','en_preparacion','casi_listo','listo',
            'recogido','en_camino','cerca_destino','entregado','cancelado'
        ) NOT NULL DEFAULT 'pendiente'");

        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique();
            $table->text('valor')->nullable();
            $table->timestamps();
        });

        DB::table('configuraciones')->insert([
            ['clave' => 'reservas_habilitadas', 'valor' => '1', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pedidos MODIFY COLUMN estado ENUM(
            'pendiente','en_preparacion','preparado','en_camino','entregado'
        ) NOT NULL DEFAULT 'pendiente'");

        Schema::dropIfExists('configuraciones');
    }
};
