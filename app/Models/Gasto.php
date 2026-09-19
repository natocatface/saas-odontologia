<?php

namespace App\Models;

use App\Models\Concerns\RegistraActividad;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gasto extends Model
{
    use HasFactory, RegistraActividad;

    protected $table = 'gastos';

    public const CATEGORIAS = [
        'insumos' => 'Insumos y materiales',
        'laboratorio' => 'Laboratorio',
        'sueldos' => 'Sueldos y honorarios',
        'alquiler' => 'Alquiler',
        'servicios' => 'Servicios (luz, agua, internet)',
        'equipos' => 'Equipos y mantenimiento',
        'marketing' => 'Marketing',
        'impuestos' => 'Impuestos',
        'otros' => 'Otros',
    ];

    public const METODOS = [
        'efectivo' => 'Efectivo',
        'tarjeta' => 'Tarjeta',
        'transferencia' => 'Transferencia',
        'qr' => 'QR',
    ];

    protected $fillable = [
        'fecha',
        'categoria',
        'descripcion',
        'monto',
        'metodo',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'monto' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getCategoriaNombreAttribute(): string
    {
        return self::CATEGORIAS[$this->categoria] ?? ucfirst((string) $this->categoria);
    }

    public function getMetodoNombreAttribute(): string
    {
        return self::METODOS[$this->metodo] ?? ucfirst((string) $this->metodo);
    }
}
