<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE incidencias MODIFY COLUMN tipo ENUM('problema','devolucion','reenvio') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE incidencias MODIFY COLUMN tipo ENUM('problema','devolucion') NOT NULL");
    }
};