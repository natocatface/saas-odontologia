<?php

namespace App\Facturacion\Domain\Contracts;

/** Firma digitalmente el XML (XAdES-BES u otro) con el certificado de la empresa. */
interface FirmanteDigital
{
    public function firmar(string $xml): string;
}
