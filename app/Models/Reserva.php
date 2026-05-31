<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'recurso_id', 'nombre', 'dni',
        'fecha', 'hora_inicio', 'duracion', 'estado', 'personas',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recurso()
    {
        return $this->belongsTo(Recurso::class);
    }

    public function getEstadoLabelAttribute(): string
    {
        return match($this->estado) {
            'pendiente'   => 'Pendiente',
            'confirmada'  => 'Confirmada',
            'cancelada'   => 'Cancelada',
            'completada'  => 'Completada',
            default       => ucfirst($this->estado),
        };
    }

    public function getDuracionLabelAttribute(): string
    {
        return match($this->duracion) {
            '1h'    => '1 hora',
            '4h'    => '4 horas',
            'dia'   => 'Día completo',
            default => $this->duracion,
        };
    }

    public function getHoraFinAttribute(): Carbon
    {
        $inicio = Carbon::parse($this->fecha->format('Y-m-d') . ' ' . $this->hora_inicio);

        return match($this->duracion) {
            '4h'  => $inicio->addHours(4),
            'dia' => $inicio->endOfDay(),
            default => $inicio->addHour(),
        };
    }

    public static function liberarVencidas(): int
    {
        $ahora = Carbon::now();
        $liberadas = 0;

        static::with('recurso')
            ->whereIn('estado', ['confirmada', 'pendiente'])
            ->get()
            ->each(function (self $reserva) use ($ahora, &$liberadas) {
                if ($ahora->greaterThan($reserva->hora_fin)) {
                    $reserva->update(['estado' => 'completada']);

                    $tieneOtraActiva = static::where('recurso_id', $reserva->recurso_id)
                        ->whereIn('estado', ['confirmada', 'pendiente'])
                        ->where('id', '!=', $reserva->id)
                        ->exists();

                    if (!$tieneOtraActiva) {
                        $reserva->recurso->update(['estado' => 'disponible']);
                    }

                    $liberadas++;
                }
            });

        return $liberadas;
    }
}
