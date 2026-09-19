<?php

namespace App\Facturacion\Application\DTO;

/**
 * DTO de entrada para anular (comunicacion de baja) un comprobante ya emitido.
 * El ERP solo necesita el id del documento y el motivo.
 */
final class AnularDocumentoDTO
{
    public function __construct(
        public readonly int $documentoId,
        public readonly string $motivo,
    ) {
    }
}
