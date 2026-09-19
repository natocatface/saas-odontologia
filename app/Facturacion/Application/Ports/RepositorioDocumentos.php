<?php

namespace App\Facturacion\Application\Ports;

use App\Facturacion\Domain\Model\Documento;

/** Puerto de persistencia. La infraestructura (Eloquent) lo implementa. */
interface RepositorioDocumentos
{
    public function guardar(Documento $documento): string; // devuelve uuid/id

    public function actualizarEstado(string $id, Documento $documento): void;

    public function buscarPorReferencia(string $referencia): ?Documento;

    public function existeIdempotencyKey(string $key): bool;

    /** Siguiente correlativo disponible para una serie (por pais). Empieza en 1. */
    public function siguienteCorrelativo(string $pais, string $serie): int;

    /**
     * Datos minimos de un comprobante para comunicar su baja.
     *
     * @return array{id:int,pais:string,tipo:string,serie:string,numero:int,estado:string,fecha_emision:?string}|null
     */
    public function datosParaBaja(int $id): ?array;

    /** Marca un comprobante como anulado y guarda ticket/CDR de la baja. */
    public function marcarAnulado(int $id, ?string $referencia, ?string $cdrPath, ?string $error = null): void;
}
