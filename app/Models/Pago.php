<?php

namespace App\Models;

use App\Models\Concerns\RegistraActividad;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pago extends Model
{
    use HasFactory, RegistraActividad;

    protected $table = 'pagos';

    public const METODOS = [
        'efectivo' => 'Efectivo',
        'tarjeta' => 'Tarjeta',
        'transferencia' => 'Transferencia',
        'qr' => 'QR',
    ];

    protected $fillable = [
        'numero',
        'paciente_id',
        'presupuesto_id',
        'fecha',
        'monto',
        'metodo',
        'referencia',
        'notas',
    ];

    /** Asigna el numero de recibo correlativo al crear el pago. */
    protected static function booted(): void
    {
        static::created(function (Pago $pago) {
            if (empty($pago->numero)) {
                $pago->numero = 'REC-'.str_pad((string) $pago->id, 6, '0', STR_PAD_LEFT);
                $pago->saveQuietly();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'monto' => 'decimal:2',
        ];
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function presupuesto(): BelongsTo
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function cuota(): HasOne
    {
        return $this->hasOne(Cuota::class);
    }

    public function getMetodoNombreAttribute(): string
    {
        return self::METODOS[$this->metodo] ?? ucfirst((string) $this->metodo);
    }

    public function getNumeroReciboAttribute(): string
    {
        return $this->numero ?: 'REC-'.str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }
}
