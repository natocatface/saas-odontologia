<?php

namespace App\Facturacion\Infrastructure\Providers\Peru;

use App\Facturacion\Domain\Contracts\FirmanteDigital;

/** Firma XAdES-BES del XML con el certificado (.pfx) de la empresa. Esqueleto Fase 0. */
final class SunatFirmante implements FirmanteDigital
{
    public function firmar(string $xml): string
    {
        // Fase 1: firmar con el certificado por empresa (openssl / robrichards/xmlseclibs).
        return $xml; // placeholder: devuelve sin firmar
    }
}
