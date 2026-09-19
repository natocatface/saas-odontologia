<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Archivo extends Model
{
    protected $table = 'archivos';

    public const CATEGORIAS = [
        'radiografia' => 'Radiografia',
        'foto' => 'Foto intraoral',
        'documento' => 'Documento',
        'consentimiento' => 'Consentimiento',
    ];

    protected $fillable = [
        'paciente_id',
        'nombre',
        'ruta',
        'mime',
        'tamano',
        'categoria',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->ruta);
    }

    public function getEsImagenAttribute(): bool
    {
        return str_starts_with((string) $this->mime, 'image/');
    }

    public function getTamanoLegibleAttribute(): string
    {
        $bytes = (int) $this->tamano;
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1).' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024).' KB';
        }

        return $bytes.' B';
    }

    public function getCategoriaNombreAttribute(): string
    {
        return self::CATEGORIAS[$this->categoria] ?? 'Archivo';
    }
}
