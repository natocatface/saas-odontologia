<?php

namespace App\Facturacion\Domain\Contracts;

use App\Facturacion\Domain\Model\Documento;
use App\Facturacion\Domain\Result\RespuestaFiscal;

/** Transmite el XML firmado a la autoridad u OSE (SOAP/REST) y devuelve su respuesta. */
interface TransmisorFiscal
{
    public function enviar(string $xmlFirmado, Documento $documento): RespuestaFiscal;
}
