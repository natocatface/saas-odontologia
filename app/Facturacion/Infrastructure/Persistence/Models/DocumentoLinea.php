<?php

namespace App\Facturacion\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Linea/detalle de un comprobante fiscal. */
class DocumentoLinea extends Model
{
    protected $table = 'documento_lineas';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'cantidad' => 'decimal:3',
            'precio' => 'decimal:2',
            'impuesto_tasa' => 'decimal:4',
            'impuesto_monto' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function documento(): BelongsTo
    {
        return $this->belongsTo(DocumentoFiscal::class, 'documento_fiscal_id');
    }
}
