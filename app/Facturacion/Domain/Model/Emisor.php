<?php

namespace App\Facturacion\Domain\Model;

/** Datos del emisor (la clinica/empresa). La identificacion fiscal es generica. */
final class Emisor
{
    public function __construct(
        public readonly string $identificacion, // RUC / NIT / RUT / CUIT / RFC
        public readonly string $razonSocial,
        public readonly ?string $direccion = null,
        public readonly array $extra = [],      // datos especificos por pais
    ) {
    }
}
