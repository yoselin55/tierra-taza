<?php

namespace App\Console\Commands;

use App\Models\Reserva;
use Illuminate\Console\Command;

class LiberarReservasVencidas extends Command
{
    protected $signature   = 'reservas:liberar-vencidas';
    protected $description = 'Completa reservas vencidas y libera sus recursos automáticamente';

    public function handle(): int
    {
        $liberadas = Reserva::liberarVencidas();
        $this->info("$liberadas reserva(s) vencida(s) liberadas.");
        return Command::SUCCESS;
    }
}
