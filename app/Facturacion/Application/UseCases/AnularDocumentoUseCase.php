<?php

namespace App\Facturacion\Application\UseCases;

use App\Facturacion\Application\DTO\AnularDocumentoDTO;
use App\Facturacion\Application\Ports\RepositorioDocumentos;
use App\Facturacion\Domain\Enum\Pais;
use App\Facturacion\Domain\Enum\TipoDocumento;
use App\Facturacion\Domain\Model\Documento;
use App\Facturacion\Domain\Model\Emisor;
use App\Facturacion\Domain\Model\Receptor;
use App\Facturacion\Domain\Result\ResultadoEmision;
use App\Facturacion\Infrastructure\Registry\RegistroProveedores;

/**
 * Caso de uso: comunicar la baja (anulacion) de un comprobante ya aceptado.
 * Reconstruye los datos minimos desde el repositorio y delega en el proveedor.
 */
final class AnularDocumentoUseCase
{
    public function __construct(
        private readonly RepositorioDocumentos $repositorio,
        private readonly RegistroProveedores $proveedores,
    ) {
    }

    public function ejecutar(AnularDocumentoDTO $dto): ResultadoEmision
    {
        $datos = $this->repositorio->datosParaBaja($dto->documentoId);

        if ($datos === null) {
            return ResultadoEmision::rechazado('Comprobante no encontrado.');
        }

        if ($datos['estado'] !== 'aceptado') {
            return ResultadoEmision::rechazado('Solo se pueden anular comprobantes aceptados por SUNAT.');
        }

        $documento = new Documento(
            pais: Pais::from($datos['pais']),
            tipo: TipoDocumento::from($datos['tipo']),
            emisor: new Emisor('', ''),                 // el RUC lo aporta la config del proveedor
            receptor: new Receptor('', 'DNI', ''),
            lineas: [],
            serie: $datos['serie'],
            numero: $datos['numero'],
            meta: [
                'documento_id' => $datos['id'],
                'fecha_emision' => $datos['fecha_emision'],
                'motivo' => $dto->motivo,
            ],
        );

        $resultado = $this->proveedores->para(Pais::from($datos['pais']))
            ->anularDocumento($documento, $dto->motivo);

        if ($resultado->exito) {
            $this->repositorio->marcarAnulado(
                $datos['id'],
                $resultado->referenciaExterna,
                $resultado->cdrPath,
            );
        }

        return $resultado;
    }
}
