<?php

namespace App\Facturacion\Infrastructure\Providers\Peru;

use App\Facturacion\Domain\Contracts\GeneradorDocumento;
use App\Facturacion\Domain\Model\Documento;

/** Genera el XML UBL 2.1 exigido por SUNAT. Esqueleto Fase 0. */
final class SunatGeneradorXml implements GeneradorDocumento
{
    public function generar(Documento $documento): string
    {
        // Fase 1: construir UBL 2.1 real (Invoice, cac:*, cbc:*) con los catalogos SUNAT.
        // Aqui devolvemos un placeholder trazable.
        return sprintf(
            '<?xml version="1.0" encoding="UTF-8"?><Invoice><!-- UBL 2.1 pendiente Fase 1 --><Total>%.2f</Total><Moneda>%s</Moneda></Invoice>',
            $documento->total(),
            $documento->moneda,
        );
    }
}
