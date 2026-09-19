<?php

namespace App\Models;

use App\Models\Concerns\RegistraActividad;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    use HasFactory, RegistraActividad;

    protected $table = 'tratamientos';

    public const CATEGORIAS = [
        'Diagnostico',
        'Preventiva',
        'Restauracion',
        'Endodoncia',
        'Cirugia',
        'Ortodoncia',
        'Periodoncia',
        'Estetica',
        'Protesis',
        'Odontopediatria',
    ];

    protected $fillable = [
        'nombre',
        'categoria',
        'descripcion',
        'precio',
        'duracion_min',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'duracion_min' => 'integer',
            'activo' => 'boolean',
        ];
    }

    public function getPrecioFormateadoAttribute(): string
    {
        return '$'.number_format((float) $this->precio, 2);
    }
}
