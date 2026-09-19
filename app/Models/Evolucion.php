<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evolucion extends Model
{
    protected $table = 'evoluciones';

    protected $fillable = [
        'paciente_id',
        'user_id',
        'fecha',
        'diente',
        'descripcion',
    ];

    protected function casts(): array
    {
        return ['fecha' => 'date'];
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
