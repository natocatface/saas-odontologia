<?php

namespace App\Models;

use App\Models\Concerns\RegistraActividad;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Cuota extends Model
{
    use HasFactory, RegistraActividad;

    protected $table = 'cuotas';

    protected $fillable = [
        'presupuesto_id',
        'pago_id',
        'numero',
        'monto',
        'vence_el',
        'pagada',
    ];

    protected function casts(): array
    {
        return [
            'vence_el' => 'date',
            'monto' => 'decimal:2',
            'pagada' => 'boolean',
        ];
    }

    public function presupuesto(): BelongsTo
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function pago(): BelongsTo
    {
        return $this->belongsTo(Pago::class);
    }

    /** Una cuota vencida es la que no esta pagada y su fecha ya paso. */
    public function getVencidaAttribute(): bool
    {
        return ! $this->pagada && $this->vence_el !== null && $this->vence_el->lt(Carbon::today());
    }

    /** Estado legible: pagada | vencida | pendiente. */
    public function getEstadoAttribute(): string
    {
        if ($this->pagada) {
            return 'pagada';
        }

        return $this->vencida ? 'vencida' : 'pendiente';
    }
}
