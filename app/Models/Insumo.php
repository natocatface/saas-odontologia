<?php

namespace App\Models;

use App\Models\Concerns\RegistraActividad;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Insumo extends Model
{
    use HasFactory, RegistraActividad;

    protected $table = 'insumos';

    public const CATEGORIAS = [
        'general' => 'General',
        'anestesia' => 'Anestesia',
        'restauracion' => 'Restauracion',
        'endodoncia' => 'Endodoncia',
        'cirugia' => 'Cirugia',
        'ortodoncia' => 'Ortodoncia',
        'descartable' => 'Descartables',
        'proteccion' => 'Proteccion / Bioseguridad',
        'limpieza' => 'Limpieza',
        'instrumental' => 'Instrumental',
    ];

    public const UNIDADES = ['unidad', 'caja', 'paquete', 'par', 'ml', 'g', 'frasco', 'rollo'];

    protected $fillable = [
        'nombre',
        'categoria',
        'unidad',
        'stock',
        'stock_minimo',
        'costo_unitario',
        'proveedor',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'stock' => 'decimal:2',
            'stock_minimo' => 'decimal:2',
            'costo_unitario' => 'decimal:2',
            'activo' => 'boolean',
        ];
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class)->orderByDesc('fecha')->orderByDesc('id');
    }

    public function getBajoStockAttribute(): bool
    {
        return (float) $this->stock <= (float) $this->stock_minimo;
    }

    public function getCategoriaNombreAttribute(): string
    {
        return self::CATEGORIAS[$this->categoria] ?? ucfirst((string) $this->categoria);
    }

    public function getValorStockAttribute(): float
    {
        return (float) $this->stock * (float) ($this->costo_unitario ?? 0);
    }
}
