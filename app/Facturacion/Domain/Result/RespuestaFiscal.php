<?php

namespace App\Facturacion\Domain\Result;

/** Respuesta cruda del transmisor hacia la autoridad (u OSE). */
final class RespuestaFiscal
{
    public function __construct(
        public readonly bool $aceptado,
        public readonly ?string $referencia = null,
        public readonly ?string $cdr = null,       // constancia (contenido o ruta)
        public readonly ?string $codigo = null,    // codigo de respuesta de la autoridad
        public readonly ?string $mensaje = null,
        public readonly bool $reintentable = false, // true en errores transitorios
        public readonly ?string $xmlPath = null,    // ruta relativa del XML firmado guardado
        public readonly ?string $cdrPath = null,    // ruta relativa del CDR (zip) guardado
    ) {
    }
}
