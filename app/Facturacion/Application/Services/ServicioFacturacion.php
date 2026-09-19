<?php

namespace App\Facturacion\Application\Services;

use App\Facturacion\Application\DTO\AnularDocumentoDTO;
use App\Facturacion\Application\DTO\EmitirFacturaDTO;
use App\Facturacion\Application\UseCases\AnularDocumentoUseCase;
use App\Facturacion\Application\UseCases\ConsultarEstadoUseCase;
use App\Facturacion\Application\UseCases\EmitirFacturaUseCase;
use App\Facturacion\Domain\Result\ResultadoConsulta;
use App\Facturacion\Domain\Result\ResultadoEmision;

/**
 * FACHADA del bounded context. Es lo UNICO que el ERP conoce.
 * Orquesta los casos de uso; no contiene logica de negocio ni de ningun pais.
 */
final class ServicioFacturacion
{
    public function __construct(
        private readonly EmitirFacturaUseCase $emitirFactura,
        private readonly ConsultarEstadoUseCase $consultarEstado,
        private readonly AnularDocumentoUseCase $anularDocumento,
    ) {
    }

    public function emitirFactura(EmitirFacturaDTO $dto): ResultadoEmision
    {
        return $this->emitirFactura->ejecutar($dto);
    }

    public function anularDocumento(AnularDocumentoDTO $dto): ResultadoEmision
    {
        return $this->anularDocumento->ejecutar($dto);
    }

    public function consultarEstado(string $referencia): ResultadoConsulta
    {
        return $this->consultarEstado->ejecutar($referencia);
    }
}
