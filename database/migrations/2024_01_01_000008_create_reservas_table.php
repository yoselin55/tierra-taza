<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('recurso_id')->constrained()->onDelete('cascade');
            $table->string('nombre');
            $table->string('dni', 8);
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->string('duracion')->default('1h'); // 1h, 4h, dia
            $table->integer('personas')->default(1);
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'completada'])->default('confirmada');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
