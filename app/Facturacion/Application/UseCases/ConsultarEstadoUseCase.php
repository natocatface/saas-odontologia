<?php

namespace App\Facturacion\Application\UseCases;

use App\Facturacion\Application\Ports\RepositorioDocumentos;
use App\Facturacion\Domain\Enum\EstadoDocumento;
use App\Facturacion\Domain\Result\ResultadoConsulta;
use App\Facturacion\Infrastructure\Registry\RegistroProveedores;

final class ConsultarEstadoUseCase
{
    public function __construct(
        private readonly RepositorioDocumentos $repositorio,
        private readonly RegistroProveedores $proveedores,
    ) {
    }

    public function ejecutar(string $referencia): ResultadoConsulta
    {
        $documento = $this->repositorio->buscarPorReferencia($referencia);

        if (! $documento) {
            return new ResultadoConsulta(EstadoDocumento::ERROR, mensaje: 'Documento no encontrado.');
        }

        return $this->proveedores->para($documento->pais)->consultarEstado($referencia);
    }
}
