<?php

namespace App\Facturacion\Domain\Model;

/** Datos del receptor (paciente/cliente). */
final class Receptor
{
    public function __construct(
        public readonly string $identificacion,   // documento fiscal
        public readonly string $tipoIdentificacion, // 'RUC','DNI','NIT','RFC'...
        public readonly string $razonSocial,
        public readonly ?string $direccion = null,
        public readonly ?string $email = null,
        public readonly array $extra = [],
    ) {
    }
}
