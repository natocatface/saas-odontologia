<?php

namespace App\Facturacion\Infrastructure\Providers\Peru;

use App\Facturacion\Domain\Contracts\TransmisorFiscal;
use App\Facturacion\Domain\Model\Documento;
use App\Facturacion\Domain\Result\RespuestaFiscal;
use App\Facturacion\Infrastructure\Providers\Peru\Greenter\GreenterEmisor;

/**
 * Envia el comprobante a SUNAT segun el driver configurado desde la UI:
 *
 *   - ninguno  : no emite; el comprobante queda pendiente.
 *   - demo     : acepta y devuelve una referencia simulada (para probar el flujo).
 *   - greenter : emision real (UBL 2.1 + firma XAdES + billService) via GreenterEmisor.
 *
 * La config llega normalizada desde App\Models\FacturacionConfig::parametrosSunat().
 */
final class SunatTransmisor implements TransmisorFiscal
{
    /** @param array<string,mixed> $config */
    public function __construct(
        private readonly array $config = [],
    ) {
    }

    public function enviar(string $xmlFirmado, Documento $documento): RespuestaFiscal
    {
        $driver = $this->config['driver'] ?? 'ninguno';
        $habilitada = (bool) ($this->config['habilitada'] ?? false);

        if (! $habilitada || $driver === 'ninguno') {
            return new RespuestaFiscal(
                aceptado: false,
                codigo: 'PENDIENTE',
                mensaje: 'Facturacion electronica deshabilitada o sin driver: el comprobante queda pendiente.',
                reintentable: true,
            );
        }

        if ($driver === 'demo') {
            return new RespuestaFiscal(
                aceptado: true,
                referencia: 'DEMO-'.strtoupper(substr(md5($xmlFirmado.$documento->total()), 0, 12)),
                codigo: '0',
                mensaje: 'Aceptado (modo demo, sin envio real a SUNAT).',
            );
        }

        if ($driver === 'greenter') {
            return (new GreenterEmisor($this->config))->enviar($documento);
        }

        return new RespuestaFiscal(
            aceptado: false,
            codigo: 'DRIVER_DESCONOCIDO',
            mensaje: "Driver de emision no soportado: {$driver}.",
        );
    }
}
