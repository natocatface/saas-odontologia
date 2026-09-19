<?php

namespace App\Facturacion\Domain\Exception;

use App\Facturacion\Domain\Enum\Pais;

class ProveedorNoConfiguradoException extends FacturacionException
{
    public static function para(Pais $pais): self
    {
        return new self("No hay proveedor de facturacion configurado para {$pais->value} ({$pais->autoridad()}).");
    }
}
