<?php

namespace App\Facturacion\Domain\Result;

use App\Facturacion\Domain\Enum\EstadoDocumento;

/** Resultado de una operacion de emision/anulacion. */
final class ResultadoEmision
{
    public function __construct(
        public readonly bool $exito,
        public readonly EstadoDocumento $estado,
        public readonly ?string $referenciaExterna = null, // ticket/CUFE/folio
        public readonly ?string $xmlPath = null,
        public readonly ?string $cdrPath = null,
        public readonly ?string $pdfPath = null,
        public readonly ?string $mensaje = null,
        public readonly array $errores = [],
    ) {
    }

    public static function aceptado(string $referencia, array $rutas = []): self
    {
        return new self(true, EstadoDocumento::ACEPTADO, $referencia,
            $rutas['xml'] ?? null, $rutas['cdr'] ?? null, $rutas['pdf'] ?? null);
    }

    public static function rechazado(string $mensaje, array $errores = []): self
    {
        return new self(false, EstadoDocumento::RECHAZADO, mensaje: $mensaje, errores: $errores);
    }
}
