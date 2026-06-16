<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE incidencias MODIFY COLUMN estado ENUM('abierta','en_proceso','resuelta','validada','rechazada') NOT NULL DEFAULT 'abierta'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE incidencias MODIFY COLUMN estado ENUM('abierta','en_proceso','resuelta') NOT NULL DEFAULT 'abierta'");
    }
};
