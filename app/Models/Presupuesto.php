<?php

namespace App\Models;

use App\Models\Concerns\RegistraActividad;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Presupuesto extends Model
{
    use HasFactory, RegistraActividad;

    protected $table = 'presupuestos';

    public const ESTADOS = [
        'borrador' => 'Borrador',
        'aprobado' => 'Aprobado',
        'rechazado' => 'Rechazado',
    ];

    protected $fillable = [
        'codigo',
        'paciente_id',
        'doctor_id',
        'fecha',
        'estado',
        'subtotal',
        'descuento',
        'total',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'subtotal' => 'decimal:2',
            'descuento' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PresupuestoItem::class);
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    public function cuotas(): HasMany
    {
        return $this->hasMany(Cuota::class)->orderBy('numero');
    }

    public function getEstadoNombreAttribute(): string
    {
        return self::ESTADOS[$this->estado] ?? ucfirst((string) $this->estado);
    }

    public function getPagadoAttribute(): float
    {
        return (float) $this->pagos->sum('monto');
    }

    public function getSaldoAttribute(): float
    {
        return max((float) $this->total - $this->pagado, 0);
    }

    /** Genera el siguiente codigo correlativo tipo PRE-000001. */
    public static function nuevoCodigo(): string
    {
        $ultimo = static::max('id') + 1;

        return 'PRE-'.str_pad((string) $ultimo, 6, '0', STR_PAD_LEFT);
    }
}
