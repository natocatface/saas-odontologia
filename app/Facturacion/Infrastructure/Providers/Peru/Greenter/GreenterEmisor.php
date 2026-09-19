<?php

namespace App\Facturacion\Infrastructure\Providers\Peru\Greenter;

use App\Facturacion\Domain\Enum\TipoDocumento;
use App\Facturacion\Domain\Model\Documento;
use App\Facturacion\Domain\Model\LineaDocumento;
use App\Facturacion\Domain\Result\RespuestaFiscal;

/**
 * Emisor real ante SUNAT usando la libreria Greenter (greenter/lite).
 *
 * Construye el comprobante UBL 2.1 desde el Documento del dominio, lo firma
 * (XAdES) y lo envia al billService de SUNAT, devolviendo el CDR.
 *
 * Todo el uso de Greenter esta protegido por class_exists: si la libreria no
 * esta instalada, se devuelve una RespuestaFiscal con instrucciones y la app
 * NO se rompe.
 */
final class GreenterEmisor
{
    /** @param array<string,mixed> $config parametros de FacturacionConfig::parametrosSunat() */
    public function __construct(private readonly array $config)
    {
    }

    public function enviar(Documento $documento): RespuestaFiscal
    {
        if (! class_exists(\Greenter\See::class)) {
            return new RespuestaFiscal(
                aceptado: false,
                codigo: 'GREENTER_AUSENTE',
                mensaje: 'La libreria Greenter no esta instalada. Ejecuta: composer require greenter/lite',
                reintentable: false,
            );
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

            $comprobante = $this->construirComprobante($documento);
            $nombre = method_exists($comprobante, 'getName') ? $comprobante->getName() : 'comprobante-'.time();

            // XML firmado (para archivo/consulta), aunque SUNAT rechace.
            $xmlPath = $this->guardarArchivo("{$nombre}.xml", $see->getXmlSigned($comprobante));

            $resultado = $see->send($comprobante);

            if (! $resultado->isSuccess()) {
                $err = $resultado->getError();

                return new RespuestaFiscal(
                    aceptado: false,
                    codigo: $err?->getCode() ?? 'ERROR',
                    mensaje: $err?->getMessage() ?? 'SUNAT rechazo el envio.',
                    reintentable: true,
                    xmlPath: $xmlPath,
                );
            }

            $cdr = $resultado->getCdrResponse();
            $aceptado = $cdr !== null && (int) $cdr->getCode() === 0;
            $cdrPath = $this->guardarArchivo("R-{$nombre}.zip", $resultado->getCdrZip() ?? '');

            return new RespuestaFiscal(
                aceptado: $aceptado,
                referencia: $nombre,
                cdr: base64_encode($resultado->getCdrZip() ?? ''),
                codigo: $cdr?->getCode() ?? '0',
                mensaje: $cdr?->getDescription() ?? 'Aceptado por SUNAT.',
                reintentable: false,
                xmlPath: $xmlPath,
                cdrPath: $cdrPath,
            );
        } catch (\Throwable $e) {
            return new RespuestaFiscal(
                aceptado: false,
                codigo: 'EXCEPCION',
                mensaje: 'Error al emitir con Greenter: '.$e->getMessage(),
                reintentable: true,
            );
        }
    }

    /** Guarda un archivo fiscal en el disco configurado y devuelve su ruta relativa. */
    private function guardarArchivo(string $nombre, string $contenido): ?string
    {
        if ($contenido === '') {
            return null;
        }

        try {
            $disco = config('facturacion.disco', 'local');
            $ruta = 'facturacion/pe/'.$nombre;
            \Illuminate\Support\Facades\Storage::disk($disco)->put($ruta, $contenido);

            return $ruta;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /** Construye un Invoice de Greenter (factura '01' o boleta '03') desde el dominio. */
    private function construirComprobante(Documento $documento): object
    {
        $cfg = $this->config;

        $company = (new \Greenter\Model\Company\Company())
            ->setRuc($cfg['ruc'])
            ->setRazonSocial($cfg['razon_social'])
            ->setNombreComercial($cfg['nombre_comercial'] ?: $cfg['razon_social'])
            ->setAddress((new \Greenter\Model\Company\Address())
                ->setUbigueo($cfg['ubigeo'] ?: '150101')
                ->setDepartamento($cfg['departamento'] ?: 'LIMA')
                ->setProvincia($cfg['provincia'] ?: 'LIMA')
                ->setDistrito($cfg['distrito'] ?: 'LIMA')
                ->setDireccion($cfg['direccion'] ?: '-'));

        $r = $documento->receptor;
        $client = (new \Greenter\Model\Client\Client())
            ->setTipoDoc($this->tipoDocReceptor($r->tipoIdentificacion))
            ->setNumDoc($r->identificacion ?: '00000000')
            ->setRznSocial($r->razonSocial ?: 'CLIENTE VARIOS');

        $esBoleta = $documento->tipo === TipoDocumento::BOLETA;
        $tipoDoc = $esBoleta ? '03' : '01';
        $serie = $documento->serie ?: ($esBoleta ? 'B001' : 'F001');
        $correlativo = (string) ($documento->numero ?? 1);

        $gravadas = $documento->subtotal();
        $igv = $documento->impuestos();
        $total = $documento->total();

        $details = array_map(fn (LineaDocumento $l) => $this->detalle($l), $documento->lineas);

        $legend = (new \Greenter\Model\Sale\Legend())
            ->setCode('1000')
            ->setValue($this->montoEnLetras($total).' '.($documento->moneda === 'USD' ? 'DOLARES AMERICANOS' : 'SOLES'));

        return (new \Greenter\Model\Sale\Invoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101')
            ->setTipoDoc($tipoDoc)
            ->setSerie($serie)
            ->setCorrelativo($correlativo)
            ->setFechaEmision(new \DateTime())
            ->setTipoMoneda($documento->moneda)
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas($gravadas)
            ->setMtoIGV($igv)
            ->setTotalImpuestos($igv)
            ->setValorVenta($gravadas)
            ->setSubTotal($total)
            ->setMtoImpVenta($total)
            ->setDetails($details)
            ->setLegends([$legend]);
    }

    private function detalle(LineaDocumento $l): object
    {
        $tasa = $l->tasaImpuesto;            // 0.18
        $base = $l->baseImponible();
        $igv = $l->impuesto();
        $precioConIgv = $l->cantidad > 0 ? round(($base + $igv) / $l->cantidad, 2) : 0;

        return (new \Greenter\Model\Sale\SaleDetail())
            ->setCodProducto($l->codigo ?: 'SERV')
            ->setUnidad('ZZ')                // servicio (odontologia)
            ->setCantidad($l->cantidad)
            ->setDescripcion($l->descripcion)
            ->setMtoBaseIgv($base)
            ->setPorcentajeIgv($tasa * 100)
            ->setIgv($igv)
            ->setTipAfeIgv('10')             // gravado - operacion onerosa
            ->setTotalImpuestos($igv)
            ->setMtoValorVenta($base)
            ->setMtoValorUnitario($l->precioUnitario)
            ->setMtoPrecioUnitario($precioConIgv);
    }

    /** Mapea el tipo de documento del receptor al catalogo 06 de SUNAT. */
    private function tipoDocReceptor(string $tipo): string
    {
        return match (strtoupper($tipo)) {
            'RUC'          => '6',
            'DNI'          => '1',
            'CE', 'CARNET' => '4',
            'PASAPORTE'    => '7',
            default        => '0',
        };
    }

    /** Convierte un importe a letras en espanol (para la leyenda del comprobante). */
    private function montoEnLetras(float $monto): string
    {
        $entero = (int) floor($monto);
        $centavos = (int) round(($monto - $entero) * 100);
        $texto = $entero === 0 ? 'CERO' : $this->numeroALetras($entero);

        return trim($texto).' CON '.str_pad((string) $centavos, 2, '0', STR_PAD_LEFT).'/100';
    }

    private function numeroALetras(int $n): string
    {
        if ($n === 0) {
            return '';
        }
        if ($n < 0) {
            return 'MENOS '.$this->numeroALetras(-$n);
        }

        $unidades = ['', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE',
            'DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE',
            'DIECIOCHO', 'DIECINUEVE', 'VEINTE'];
        $decenas = ['', '', 'VEINTI', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
        $centenas = ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS',
            'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];

        if ($n <= 20) {
            return $unidades[$n];
        }
        if ($n < 30) {
            return $decenas[2].($n % 10 ? $unidades[$n % 10] : '');
        }
        if ($n < 100) {
            $d = intdiv($n, 10);
            $u = $n % 10;

            return $decenas[$d].($u ? ' Y '.$unidades[$u] : '');
        }
        if ($n === 100) {
            return 'CIEN';
        }
        if ($n < 1000) {
            $c = intdiv($n, 100);
            $resto = $n % 100;

            return $centenas[$c].($resto ? ' '.$this->numeroALetras($resto) : '');
        }
        if ($n < 1000000) {
            $miles = intdiv($n, 1000);
            $resto = $n % 1000;
            $prefijo = $miles === 1 ? 'MIL' : $this->numeroALetras($miles).' MIL';

            return $prefijo.($resto ? ' '.$this->numeroALetras($resto) : '');
        }

        $millones = intdiv($n, 1000000);
        $resto = $n % 1000000;
        $prefijo = $millones === 1 ? 'UN MILLON' : $this->numeroALetras($millones).' MILLONES';

        return $prefijo.($resto ? ' '.$this->numeroALetras($resto) : '');
    }
}
