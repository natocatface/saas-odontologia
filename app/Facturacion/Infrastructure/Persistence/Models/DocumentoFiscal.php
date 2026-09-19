<?php

namespace App\Facturacion\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Eloquent de persistencia (detalle de infraestructura).
 * El dominio NO lo usa; solo el repositorio lo mapea desde/hacia Documento.
 */
class DocumentoFiscal extends Model
{
    protected $table = 'documentos_fiscales';

    protected $guarded = [];

    public function lineas(): HasMany
    {
        return $this->hasMany(DocumentoLinea::class, 'documento_fiscal_id');
    }

    protected function casts(): array
    {
        return [
            'emisor_snapshot' => 'array',
            'receptor_snapshot' => 'array',
            'subtotal' => 'decimal:2',
            'impuestos' => 'decimal:2',
            'total' => 'decimal:2',
            'intentos' => 'integer',
        ];
    }
}
