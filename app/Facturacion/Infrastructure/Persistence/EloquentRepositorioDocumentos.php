<?php

namespace App\Facturacion\Infrastructure\Persistence;

use App\Facturacion\Application\Ports\RepositorioDocumentos;
use App\Facturacion\Domain\Model\Documento;
use App\Facturacion\Infrastructure\Persistence\Models\DocumentoFiscal;
use Illuminate\Support\Str;

/** Adaptador de persistencia con Eloquent. Mapea el aggregate Documento a la tabla. */
final class EloquentRepositorioDocumentos implements RepositorioDocumentos
{
    public function guardar(Documento $documento): string
    {
        $uuid = (string) Str::uuid();

        $modelo = DocumentoFiscal::create([
            'uuid' => $uuid,
            'empresa_id' => $documento->meta['empresa_id'] ?? null,
            'pais' => $documento->pais->value,
            'tipo' => $documento->tipo->value,
            'serie' => $documento->serie,
            'numero' => $documento->numero,
            'emisor_snapshot' => (array) $documento->emisor,
            'receptor_snapshot' => (array) $documento->receptor,
            'moneda' => $documento->moneda,
            'subtotal' => $documento->subtotal(),
            'impuestos' => $documento->impuestos(),
            'total' => $documento->total(),
            'estado' => $documento->estado()->value,
            'idempotency_key' => $documento->meta['idempotency_key'] ?? null,
            'origen_tipo' => $documento->meta['origen']['tipo'] ?? null,
            'origen_id' => $documento->meta['origen']['id'] ?? null,
            'intentos' => 0,
        ]);

        // Persistir las lineas del comprobante (para la representacion impresa).
        foreach ($documento->lineas as $linea) {
            $modelo->lineas()->create([
                'descripcion' => $linea->descripcion,
                'cantidad' => $linea->cantidad,
                'precio' => $linea->precioUnitario,
                'impuesto_tasa' => $linea->tasaImpuesto,
                'impuesto_monto' => $linea->impuesto(),
                'total' => $linea->total(),
            ]);
        }

        return $uuid;
    }

    public function actualizarEstado(string $idempotencyKey, Documento $documento): void
    {
        DocumentoFiscal::where('idempotency_key', $idempotencyKey)->update([
            'estado' => $documento->estado()->value,
            'referencia_externa' => $documento->referenciaExterna(),
            'xml_path' => $documento->xmlPath(),
            'cdr_path' => $documento->cdrPath(),
        ]);
    }

    public function buscarPorReferencia(string $referencia): ?Documento
    {
        // Fase 1: rehidratar el aggregate desde la fila. Aqui devolvemos null si no existe.
        return null;
    }

    public function existeIdempotencyKey(string $key): bool
    {
        return DocumentoFiscal::where('idempotency_key', $key)->exists();
    }

    public function siguienteCorrelativo(string $pais, string $serie): int
    {
        $max = DocumentoFiscal::where('pais', $pais)
            ->where('serie', $serie)
            ->max('numero');

        return (int) $max + 1;
    }

    public function datosParaBaja(int $id): ?array
    {
        $row = DocumentoFiscal::find($id);

        if (! $row) {
            return null;
        }

        return [
            'id' => (int) $row->id,
            'pais' => (string) $row->pais,
            'tipo' => (string) $row->tipo,
            'serie' => (string) $row->serie,
            'numero' => (int) $row->numero,
            'estado' => (string) $row->estado,
            'fecha_emision' => optional($row->created_at)->format('Y-m-d'),
        ];
    }

    public function marcarAnulado(int $id, ?string $referencia, ?string $cdrPath, ?string $error = null): void
    {
        DocumentoFiscal::where('id', $id)->update([
            'estado' => \App\Facturacion\Domain\Enum\EstadoDocumento::ANULADO->value,
            'referencia_externa' => $referencia,
            'cdr_path' => $cdrPath,
            'error' => $error,
        ]);
    }
}
