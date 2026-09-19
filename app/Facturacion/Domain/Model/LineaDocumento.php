<?php

namespace App\Facturacion\Domain\Model;

final class LineaDocumento
{
    public function __construct(
        public readonly string $descripcion,
        public readonly float $cantidad,
        public readonly float $precioUnitario,
        public readonly float $tasaImpuesto = 0.18,
        public readonly ?string $codigo = null,
    ) {
    }

    public function baseImponible(): float
    {
        return round($this->cantidad * $this->precioUnitario, 2);
    }

    public function impuesto(): float
    {
        return round($this->baseImponible() * $this->tasaImpuesto, 2);
    }

    public function total(): float
    {
        return round($this->baseImponible() + $this->impuesto(), 2);
    }
}
