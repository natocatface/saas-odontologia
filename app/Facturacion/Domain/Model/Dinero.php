<?php

namespace App\Facturacion\Domain\Model;

use InvalidArgumentException;

/** Value object inmutable: importe + moneda (ISO-4217). */
final class Dinero
{
    public function __construct(
        public readonly float $importe,
        public readonly string $moneda = 'PEN',
    ) {
        if (strlen($moneda) !== 3) {
            throw new InvalidArgumentException('La moneda debe ser un codigo ISO-4217 de 3 letras.');
        }
    }

    public function sumar(Dinero $otro): self
    {
        $this->asegurarMismaMoneda($otro);

        return new self(round($this->importe + $otro->importe, 2), $this->moneda);
    }

    private function asegurarMismaMoneda(Dinero $otro): void
    {
        if ($this->moneda !== $otro->moneda) {
            throw new InvalidArgumentException('No se pueden operar montos de distinta moneda.');
        }
    }
}
