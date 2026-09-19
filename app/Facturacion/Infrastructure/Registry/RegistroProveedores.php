<?php

namespace App\Facturacion\Infrastructure\Registry;

use App\Facturacion\Domain\Contracts\ProveedorFacturacion;
use App\Facturacion\Domain\Enum\Pais;
use App\Facturacion\Domain\Exception\ProveedorNoConfiguradoException;
use Illuminate\Contracts\Container\Container;

/**
 * REGISTRO (strategy resolver). Resuelve el ProveedorFacturacion de un pais
 * a partir del mapa `config('facturacion.proveedores')`.
 *
 * Agregar un pais = agregar una linea al config. Esta clase NO cambia.
 */
final class RegistroProveedores
{
    /** @param array<string, class-string<ProveedorFacturacion>> $mapa */
    public function __construct(
        private readonly Container $container,
        private readonly array $mapa,
    ) {
    }

    public function para(Pais $pais): ProveedorFacturacion
    {
        $clase = $this->mapa[$pais->value] ?? null;

        if (! $clase) {
            throw ProveedorNoConfiguradoException::para($pais);
        }

        return $this->container->make($clase);
    }

    public function paisesSoportados(): array
    {
        return array_keys($this->mapa);
    }
}
