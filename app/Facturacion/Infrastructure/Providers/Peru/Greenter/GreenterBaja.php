<?php

namespace App\Facturacion\Infrastructure\Providers\Peru\Greenter;

use App\Facturacion\Domain\Model\Documento;
use App\Facturacion\Domain\Result\RespuestaFiscal;

/**
 * Comunicacion de Baja (anulacion) ante SUNAT con Greenter.
 *
 * Genera un documento "Voided" (Resumen de Anulacion RA), lo envia -> obtiene
 * un ticket -> consulta el estado (con reintentos) y devuelve el CDR.
 *
 * Nota: la comunicacion de baja aplica a facturas y notas. Las boletas se anulan
 * por Resumen Diario (no cubierto aqui).
 */
final class GreenterBaja
{
    /** @param array<string,mixed> $config */
    public function __construct(private readonly array $config)
    {
    }

    public function comunicar(Documento $documento, string $motivo): RespuestaFiscal
    {
        if (! class_exists(\Greenter\See::class)) {
            return new RespuestaFiscal(false, codigo: 'GREENTER_AUSENTE', mensaje: 'Greenter no esta instalado (composer require greenter/lite).');
        }

        $cfg = $this->config;

        if (empty($cfg['cert_path']) || ! is_file($cfg['cert_path'])) {
            return new RespuestaFiscal(false, codigo: 'CERT_NO_ENCONTRADO', mensaje: 'No se encontro el certificado digital (.pem).');
        }

        try {
            $see = new \Greenter\See();
            $see->setCertificate(file_get_contents($cfg['cert_path']));
            $see->setService(($cfg['entorno'] ?? 'beta') === 'produccion'
                ? \Greenter\Ws\Services\SunatEndpoints::FE_PRODUCCION
                : \Greenter\Ws\Services\SunatEndpoints::FE_BETA);
            $see->setClaveSOL($cfg['ruc'], $cfg['usuario_sol'], $cfg['clave_sol']);

            $tipoDoc = ['factura' => '01', 'boleta' => '03', 'nota_credito' => '07', 'nota_debito' => '08'][$documento->tipo->value] ?? '01';
            $fechaEmision = $documento->meta['fecha_emision'] ?? date('Y-m-d');
            $correlativoRa = date('His'); // correlativo del RA (unico por segundo)

            $company = (new \Greenter\Model\Company\Company())
                ->setRuc($cfg['ruc'])
                ->setRazonSocial($cfg['razon_social'])
                ->setAddress((new \Greenter\Model\Company\Address())
                    ->setUbigueo($cfg['ubigeo'] ?: '150101')
                    ->setDepartamento($cfg['departamento'] ?: 'LIMA')
                    ->setProvincia($cfg['provincia'] ?: 'LIMA')
                    ->setDistrito($cfg['distrito'] ?: 'LIMA')
                    ->setDireccion($cfg['direccion'] ?: '-'));

            $detalle = (new \Greenter\Model\Voided\VoidedDetail())
                ->setTipoDoc($tipoDoc)
                ->setSerie($documento->serie)
                ->setCorrelativo((string) $documento->numero)
                ->setDesMotivoBaja(mb_substr($motivo, 0, 100));

            $voided = (new \Greenter\Model\Voided\Voided())
                ->setCorrelativo($correlativoRa)
                ->setFecGeneracion(new \DateTime($fechaEmision))
                ->setFecComunicacion(new \DateTime())
                ->setCompany($company)
                ->setDetails([$detalle]);

            $envio = $see->send($voided);

            if (! $envio->isSuccess()) {
                $err = $envio->getError();

                return new RespuestaFiscal(false, codigo: $err?->getCode() ?? 'ERROR', mensaje: $err?->getMessage() ?? 'SUNAT rechazo la comunicacion de baja.', reintentable: true);
            }

            $ticket = $envio->getTicket();

            // Consultar el estado del ticket con algunos reintentos (SUNAT es asincrono).
            $status = null;
            for ($i = 0; $i < 4; $i++) {
                $status = $see->getStatus($ticket);
                if ($status->isSuccess()) {
                    break;
                }
                sleep(3);
            }

            if ($status === null || ! $status->isSuccess()) {
                return new RespuestaFiscal(
                    aceptado: false,
                    referencia: $ticket,
                    codigo: 'EN_PROCESO',
                    mensaje: "Baja enviada, en proceso en SUNAT. Ticket: {$ticket}. Reintenta la consulta mas tarde.",
                    reintentable: true,
                );
            }

            $cdrPath = $this->guardarCdr("R-BAJA-{$documento->serie}-{$documento->numero}.zip", $status->getCdrZip() ?? '');
            $aceptado = (int) ($status->getCode() ?? 0) === 0;

            return new RespuestaFiscal(
                aceptado: $aceptado,
                referencia: $ticket,
                cdr: base64_encode($status->getCdrZip() ?? ''),
                codigo: (string) ($status->getCode() ?? '0'),
                mensaje: $aceptado ? 'Comunicacion de baja aceptada por SUNAT.' : ($status->getError()?->getMessage() ?? 'La baja no fue aceptada.'),
                cdrPath: $cdrPath,
            );
        } catch (\Throwable $e) {
            return new RespuestaFiscal(false, codigo: 'EXCEPCION', mensaje: 'Error al comunicar la baja: '.$e->getMessage(), reintentable: true);
        }
    }

    private function guardarCdr(string $nombre, string $contenido): ?string
    {
        if ($contenido === '') {
            return null;
        }

        try {
            $disco = config('facturacion.disco', 'local');
            $ruta = 'facturacion/pe/bajas/'.$nombre;
            \Illuminate\Support\Facades\Storage::disk($disco)->put($ruta, $contenido);

            return $ruta;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
