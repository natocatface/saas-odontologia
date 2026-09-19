<?php

namespace App\Facturacion\Domain\Contracts;

use App\Facturacion\Domain\Enum\Pais;
use App\Facturacion\Domain\Model\Documento;
use App\Facturacion\Domain\Result\ResultadoConsulta;
use App\Facturacion\Domain\Result\ResultadoEmision;

/**
 * Contrato comun para TODA autoridad fiscal (SUNAT, DIAN, SII, AFIP, SAT...).
 * El ERP y la capa de Aplicacion dependen SOLO de esta interfaz.
 * Agregar un pais = implementar esta interfaz en Infrastructure/Providers/{Pais}.
 */
interface ProveedorFacturacion
{
    public function emitirFactura(Documento $documento): ResultadoEmision;

    public function emitirNotaCredito(Documento $documento): ResultadoEmision;

    public function anularDocumento(Documento $documento, string $motivo): ResultadoEmision;

    public function consultarEstado(string $referencia): ResultadoConsulta;

    /** Pais que atiende esta estrategia (para el registro). */
    public function pais(): Pais;
}
