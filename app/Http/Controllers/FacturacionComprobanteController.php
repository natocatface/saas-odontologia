<?php

namespace App\Http\Controllers;

use App\Facturacion\Infrastructure\Persistence\Models\DocumentoFiscal;
use App\Models\FacturacionConfig;
use App\Support\NumeroALetras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Listado de comprobantes electronicos emitidos (documentos_fiscales)
 * con estado y descarga de XML firmado / CDR. Solo administradores.
 */
class FacturacionComprobanteController extends Controller
{
    public function index(Request $request): View
    {
        // Tolerante a que aun no se haya migrado el modulo de facturacion.
        if (! Schema::hasTable('documentos_fiscales')) {
            return view('facturacion.comprobantes', [
                'comprobantes' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20),
                'stats'        => ['total' => 0, 'aceptado' => 0, 'rechazado' => 0, 'pendiente' => 0],
                'buscar'       => '',
                'estado'       => '',
                'tipo'         => '',
                'sinTabla'     => true,
            ]);
        }

        $buscar = trim((string) $request->query('buscar', ''));
        $estado = (string) $request->query('estado', '');
        $tipo = (string) $request->query('tipo', '');

        $comprobantes = DocumentoFiscal::query()
            ->when($estado !== '', fn ($q) => $q->where('estado', $estado))
            ->when($tipo !== '', fn ($q) => $q->where('tipo', $tipo))
            ->when($buscar !== '', fn ($q) => $q->where(function ($s) use ($buscar) {
                $s->where('serie', 'like', "%{$buscar}%")
                    ->orWhere('numero', 'like', "%{$buscar}%")
                    ->orWhere('referencia_externa', 'like', "%{$buscar}%")
                    ->orWhere('receptor_snapshot', 'like', "%{$buscar}%");
            }))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total'     => DocumentoFiscal::count(),
            'aceptado'  => DocumentoFiscal::where('estado', 'aceptado')->count(),
            'rechazado' => DocumentoFiscal::where('estado', 'rechazado')->count(),
            'pendiente' => DocumentoFiscal::whereIn('estado', ['borrador', 'enviado', 'error'])->count(),
        ];

        $sinTabla = false;

        return view('facturacion.comprobantes', compact('comprobantes', 'stats', 'buscar', 'estado', 'tipo', 'sinTabla'));
    }

    /** Comunicacion de baja (anulacion) de un comprobante aceptado. */
    public function anular(Request $request, DocumentoFiscal $documento)
    {
        $data = $request->validate([
            'motivo' => ['required', 'string', 'min:3', 'max:100'],
        ], [
            'motivo.required' => 'Indica el motivo de la anulacion.',
            'motivo.max' => 'El motivo no puede superar 100 caracteres.',
        ]);

        if ($documento->estado !== 'aceptado') {
            return back()->with('error', 'Solo se pueden anular comprobantes aceptados por SUNAT.');
        }

        try {
            $servicio = app(\App\Facturacion\Application\Services\ServicioFacturacion::class);
            $resultado = $servicio->anularDocumento(
                new \App\Facturacion\Application\DTO\AnularDocumentoDTO((int) $documento->id, $data['motivo'])
            );

            if ($resultado->exito) {
                return back()->with('status', 'Comprobante anulado: '.($resultado->mensaje ?: 'baja comunicada a SUNAT').'.');
            }

            return back()->with('error', 'No se pudo anular: '.($resultado->mensaje ?: implode(' ', $resultado->errores)).'.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Error al anular el comprobante: '.$e->getMessage());
        }
    }

    /** Representacion impresa (PDF via navegador) con codigo QR de SUNAT. */
    public function representacion(DocumentoFiscal $documento): View
    {
        $documento->load('lineas');
        $emisor = FacturacionConfig::parametrosSunat();
        $receptor = $documento->receptor_snapshot ?? [];

        $tipoCodigo = [
            'factura' => '01', 'boleta' => '03',
            'nota_credito' => '07', 'nota_debito' => '08',
        ][$documento->tipo] ?? '01';

        $tipoDocReceptor = [
            'RUC' => '6', 'DNI' => '1', 'CE' => '4', 'CARNET' => '4', 'PASAPORTE' => '7',
        ][strtoupper($receptor['tipoIdentificacion'] ?? '')] ?? '0';

        $digest = $this->digestValueDeXml($documento->xml_path);
        $numeroFmt = str_pad((string) $documento->numero, 6, '0', STR_PAD_LEFT);

        // Cadena QR de SUNAT: RUC|Tipo|Serie|Numero|IGV|Total|Fecha|TipoDocRecep|NumRecep|Hash
        $qr = implode('|', [
            $emisor['ruc'] ?: ($documento->emisor_snapshot['identificacion'] ?? ''),
            $tipoCodigo,
            $documento->serie,
            $numeroFmt,
            number_format((float) $documento->impuestos, 2, '.', ''),
            number_format((float) $documento->total, 2, '.', ''),
            optional($documento->created_at)->format('Y-m-d'),
            $tipoDocReceptor,
            $receptor['identificacion'] ?? '',
            $digest,
        ]);

        return view('facturacion.representacion', [
            'doc' => $documento,
            'emisor' => $emisor,
            'receptor' => $receptor,
            'tipoCodigo' => $tipoCodigo,
            'numeroFmt' => $numeroFmt,
            'qr' => $qr,
            'enLetras' => NumeroALetras::importe((float) $documento->total, $documento->moneda),
            'config' => \App\Models\Configuracion::todas(),
        ]);
    }

    /** Extrae el ds:DigestValue del XML firmado guardado (si existe). */
    private function digestValueDeXml(?string $rutaXml): string
    {
        if ($rutaXml === null || $rutaXml === '') {
            return '';
        }

        try {
            $disco = config('facturacion.disco', 'local');
            if (! Storage::disk($disco)->exists($rutaXml)) {
                return '';
            }
            $xml = Storage::disk($disco)->get($rutaXml);
            if (preg_match('/<ds:DigestValue>(.*?)<\/ds:DigestValue>/s', $xml, $m)) {
                return trim($m[1]);
            }
        } catch (\Throwable $e) {
            // silencioso: la representacion se muestra sin hash
        }

        return '';
    }

    public function descargarXml(DocumentoFiscal $documento): StreamedResponse
    {
        return $this->descargar($documento->xml_path, ($documento->serie.'-'.$documento->numero).'.xml');
    }

    public function descargarCdr(DocumentoFiscal $documento): StreamedResponse
    {
        return $this->descargar($documento->cdr_path, 'R-'.($documento->serie.'-'.$documento->numero).'.zip');
    }

    private function descargar(?string $ruta, string $nombreDescarga): StreamedResponse
    {
        $disco = config('facturacion.disco', 'local');

        abort_if($ruta === null || $ruta === '' || ! Storage::disk($disco)->exists($ruta), 404, 'Archivo no disponible.');

        return Storage::disk($disco)->download($ruta, $nombreDescarga);
    }
}
