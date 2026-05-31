<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('fecha')->useCurrent();
            $table->enum('estado', ['pendiente', 'en_preparacion', 'preparado', 'en_camino', 'entregado'])->default('pendiente');
            $table->decimal('total', 10, 2);
            $table->enum('metodo_pago', ['yape', 'plin', 'tarjeta', 'efectivo', 'transferencia'])->nullable();
            $table->string('nombre_cliente')->nullable();
            $table->string('dni_cliente', 8)->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
