<?php

namespace App\Facturacion\Domain\Result;

use App\Facturacion\Domain\Enum\EstadoDocumento;

final class ResultadoConsulta
{
    public function __construct(
        public readonly EstadoDocumento $estado,
        public readonly ?string $referenciaExterna = null,
        public readonly ?string $mensaje = null,
        public readonly array $detalle = [],
    ) {
    }
}
