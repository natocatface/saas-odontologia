<?php

namespace App\Facturacion\Domain\Enum;

/** Tipo de comprobante (nombre neutral; cada pais lo mapea a su codigo local). */
enum TipoDocumento: string
{
    case FACTURA = 'factura';
    case BOLETA = 'boleta';
    case NOTA_CREDITO = 'nota_credito';
    case NOTA_DEBITO = 'nota_debito';
}
