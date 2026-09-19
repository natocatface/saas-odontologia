<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresupuestoItem extends Model
{
    use HasFactory;

    protected $table = 'presupuesto_items';

    protected $fillable = [
        'presupuesto_id',
        'tratamiento_id',
        'descripcion',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'realizado',
        'realizado_at',
    ];

    protected function casts(): array
    {
        return [
            'cantidad' => 'integer',
            'precio_unitario' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'realizado' => 'boolean',
            'realizado_at' => 'date',
        ];
    }

    public function presupuesto(): BelongsTo
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function tratamiento(): BelongsTo
    {
        return $this->belongsTo(Tratamiento::class);
    }
}
