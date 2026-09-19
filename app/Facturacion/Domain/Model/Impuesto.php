<?php

namespace App\Facturacion\Domain\Model;

/** Impuesto neutral (IGV/IVA/etc.). Cada pais mapea el 'tipo' a su catalogo. */
final class Impuesto
{
    public function __construct(
        public readonly string $tipo,   // p.ej. 'IGV', 'IVA'
        public readonly float $tasa,    // 0.18, 0.19...
        public readonly float $base,
    ) {
    }

    public function monto(): float
    {
        return round($this->base * $this->tasa, 2);
    }
}
