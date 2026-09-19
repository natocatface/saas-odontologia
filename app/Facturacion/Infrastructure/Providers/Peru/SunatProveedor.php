<?php

namespace App\Facturacion\Infrastructure\Providers\Peru;

use App\Facturacion\Domain\Contracts\FirmanteDigital;
use App\Facturacion\Domain\Contracts\GeneradorDocumento;
use App\Facturacion\Domain\Contracts\ProveedorFacturacion;
use App\Facturacion\Domain\Contracts\TransmisorFiscal;
use App\Facturacion\Domain\Enum\EstadoDocumento;
use App\Facturacion\Domain\Enum\Pais;
use App\Facturacion\Domain\Model\Documento;
use App\Facturacion\Domain\Result\ResultadoConsulta;
use App\Facturacion\Domain\Result\ResultadoEmision;
use App\Facturacion\Infrastructure\Providers\Peru\Greenter\GreenterBaja;

/**
 * Adaptador PERU / SUNAT. Implementa la interfaz comun orquestando:
 * generar XML (UBL 2.1) -> firmar (XAdES) -> transmitir (SUNAT u OSE).
 *
 * NOTA: esqueleto de Fase 0. La integracion real (UBL, WSDL, CDR) se completa en Fase 1.
 */
final class SunatProveedor implements ProveedorFacturacion
{
    /** @param array<string,mixed> $config parametros de FacturacionConfig::parametrosSunat() */
    public function __construct(
        private readonly GeneradorDocumento $generador,
        private readonly FirmanteDigital $firmante,
        private readonly TransmisorFiscal $transmisor,
        private readonly array $config = [],
    ) {
    }

    public function emitirFactura(Documento $documento): ResultadoEmision
    {
        $xml = $this->generador->generar($documento);
        $xmlFirmado = $this->firmante->firmar($xml);
        $respuesta = $this->transmisor->enviar($xmlFirmado, $documento);

        if ($respuesta->aceptado) {
            $documento->adjuntarRutas($respuesta->xmlPath, $respuesta->cdrPath);
            $documento->marcarAceptado($respuesta->referencia ?? '');

            return ResultadoEmision::aceptado($respuesta->referencia ?? '', [
                'xml' => $respuesta->xmlPath,
                'cdr' => $respuesta->cdrPath,
            ]);
        }

        $documento->marcarRechazado();

        return ResultadoEmision::rechazado($respuesta->mensaje ?? 'Rechazado por SUNAT', [$respuesta->codigo]);
    }

    public function emitirNotaCredito(Documento $documento): ResultadoEmision
    {
        // Reutiliza el mismo pipeline con el tipo NOTA_CREDITO (Fase 1).
        return $this->emitirFactura($documento);
    }

    public function anularDocumento(Documento $documento, string $motivo): ResultadoEmision
    {
        $driver = $this->config['driver'] ?? 'ninguno';

        if (! ($this->config['habilitada'] ?? false) || $driver === 'ninguno') {
            return ResultadoEmision::rechazado('Facturacion deshabilitada o sin driver: no se puede anular.');
        }

        if ($driver === 'demo') {
            return new ResultadoEmision(
                true,
                EstadoDocumento::ANULADO,
                'BAJA-DEMO-'.strtoupper(substr(md5($documento->serie.$documento->numero), 0, 10)),
                mensaje: 'Baja simulada (modo demo, sin envio real a SUNAT).',
            );
        }

        if ($driver === 'greenter') {
            $r = (new GreenterBaja($this->config))->comunicar($documento, $motivo);

            if ($r->aceptado) {
                return new ResultadoEmision(true, EstadoDocumento::ANULADO, $r->referencia, cdrPath: $r->cdrPath, mensaje: $r->mensaje);
            }

            return ResultadoEmision::rechazado($r->mensaje ?? 'No se pudo comunicar la baja.', [$r->codigo]);
        }

        return ResultadoEmision::rechazado("Driver de emision no soportado: {$driver}.");
    }

    public function consultarEstado(string $referencia): ResultadoConsulta
    {
        // Consulta de ticket/CDR a SUNAT (Fase 1).
        return new ResultadoConsulta(\App\Facturacion\Domain\Enum\EstadoDocumento::ENVIADO, $referencia, 'Consulta no implementada aun.');
    }

    public function pais(): Pais
    {
        return Pais::PE;
    }
}
