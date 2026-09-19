<?php

namespace App\Models;

use App\Models\Concerns\RegistraActividad;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Cita extends Model
{
    use HasFactory, RegistraActividad;

    protected $table = 'citas';

    public const ESTADOS = [
        'pendiente' => 'Pendiente',
        'confirmada' => 'Confirmada',
        'completada' => 'Completada',
        'cancelada' => 'Cancelada',
    ];

    protected $fillable = [
        'token',
        'paciente_id',
        'doctor_id',
        'silla',
        'fecha',
        'hora',
        'motivo',
        'estado',
        'notas',
        'recordatorio_enviado_en',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'recordatorio_enviado_en' => 'datetime',
        ];
    }

    /** Genera un token unico al crear la cita. */
    protected static function booted(): void
    {
        static::creating(function (Cita $cita) {
            if (empty($cita->token)) {
                $cita->token = Str::random(40);
            }
        });
    }

    /** Devuelve el token, generandolo si la cita aun no lo tiene. */
    public function asegurarToken(): string
    {
        if (empty($this->token)) {
            $this->token = Str::random(40);
            $this->saveQuietly();
        }

        return $this->token;
    }

    /** URL publica de confirmacion de la cita. */
    public function getUrlConfirmacionAttribute(): string
    {
        return route('cita.confirmar', $this->asegurarToken());
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function getEstadoNombreAttribute(): string
    {
        return self::ESTADOS[$this->estado] ?? ucfirst((string) $this->estado);
    }
}
