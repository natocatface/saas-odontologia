<?php

namespace App\Facturacion\Domain\Contracts;

use App\Facturacion\Domain\Model\Documento;

/** Genera el XML/estructura del comprobante segun el estandar del pais (UBL 2.1, DIAN, DTE...). */
interface GeneradorDocumento
{
    public function generar(Documento $documento): string;
}
