<?php

namespace App\Facturacion\Domain\Enum;

/** Ciclo de vida del comprobante, independiente del pais. */
enum EstadoDocumento: string
{
    case BORRADOR = 'borrador';
    case ENVIADO = 'enviado';
    case ACEPTADO = 'aceptado';
    case RECHAZADO = 'rechazado';
    case ANULADO = 'anulado';
    case ERROR = 'error';

    public function esFinal(): bool
    {
        return in_array($this, [self::ACEPTADO, self::RECHAZADO, self::ANULADO], true);
    }
}
