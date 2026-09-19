<?php

namespace App\Facturacion\Application\UseCases;

use App\Facturacion\Application\DTO\EmitirFacturaDTO;
use App\Facturacion\Application\Ports\RepositorioDocumentos;
use App\Facturacion\Domain\Enum\Pais;
use App\Facturacion\Domain\Enum\TipoDocumento;
use App\Facturacion\Domain\Model\Documento;
use App\Facturacion\Domain\Model\Emisor;
use App\Facturacion\Domain\Model\LineaDocumento;
use App\Facturacion\Domain\Model\Receptor;
use App\Facturacion\Domain\Result\ResultadoEmision;
use App\Facturacion\Infrastructure\Registry\RegistroProveedores;

/**
 * Caso de uso: emitir una factura/boleta.
 * Construye el Documento (dominio) desde el DTO, aplica idempotencia,
 * persiste y delega en el proveedor del pais. El envio real puede ser
 * asincrono (ProcesarEmisionJob); aqui se muestra el flujo sincrono base.
 */
final class EmitirFacturaUseCase
{
    public function __construct(
        private readonly RepositorioDocumentos $repositorio,
        private readonly RegistroProveedores $proveedores,
    ) {
    }

    public function ejecutar(EmitirFacturaDTO $dto): ResultadoEmision
    {
        // 1. Idempotencia: no duplicar comprobantes.
        if ($this->repositorio->existeIdempotencyKey($dto->idempotencyKey())) {
            return ResultadoEmision::rechazado('Ya existe un comprobante para este origen (idempotencia).');
        }

        // 2. Serie + correlativo: la serie llega en el DTO (F001/B001...); el
        //    correlativo se asigna automaticamente como el siguiente de esa serie.
        $serie = $dto->serie ?: ($dto->tipo === TipoDocumento::BOLETA->value ? 'B001' : 'F001');
        $correlativo = $this->repositorio->siguienteCorrelativo($dto->pais, $serie);

        // 3. Construir el aggregate de dominio.
        $documento = $this->construirDocumento($dto, $serie, $correlativo);

        // 4. Persistir en estado BORRADOR.
        $this->repositorio->guardar($documento);

        // 5. Resolver la estrategia del pais y emitir.
        $proveedor = $this->proveedores->para(Pais::from($dto->pais));
        $resultado = $proveedor->emitirFactura($documento);

        // 6. Persistir el nuevo estado (aceptado/rechazado).
        $this->repositorio->actualizarEstado($dto->idempotencyKey(), $documento);

        return $resultado;
    }

    private function construirDocumento(EmitirFacturaDTO $dto, string $serie, int $correlativo): Documento
    {
        $lineas = array_map(fn (array $i) => new LineaDocumento(
            descripcion: $i['descripcion'],
            cantidad: (float) $i['cantidad'],
            precioUnitario: (float) $i['precio'],
            tasaImpuesto: (float) ($i['tasa'] ?? 0.18),
            codigo: $i['codigo'] ?? null,
        ), $dto->items);

        return new Documento(
            pais: Pais::from($dto->pais),
            tipo: TipoDocumento::from($dto->tipo),
            emisor: new Emisor('SIN-CONFIGURAR', 'Empresa '.$dto->empresaId), // se resuelve desde config/empresa
            receptor: new Receptor(
                identificacion: $dto->receptor['identificacion'] ?? '',
                tipoIdentificacion: $dto->receptor['tipoIdentificacion'] ?? 'DNI',
                razonSocial: $dto->receptor['razonSocial'] ?? 'Cliente',
                direccion: $dto->receptor['direccion'] ?? null,
                email: $dto->receptor['email'] ?? null,
            ),
            lineas: $lineas,
            moneda: $dto->moneda,
            serie: $serie,
            numero: $correlativo,
            meta: ['idempotency_key' => $dto->idempotencyKey(), 'origen' => $dto->origen, 'empresa_id' => $dto->empresaId],
        );
    }
}
