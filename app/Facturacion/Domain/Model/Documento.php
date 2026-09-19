<?php

namespace App\Facturacion\Domain\Model;

use App\Facturacion\Domain\Enum\EstadoDocumento;
use App\Facturacion\Domain\Enum\Pais;
use App\Facturacion\Domain\Enum\TipoDocumento;

/**
 * Aggregate root del bounded context de Facturacion.
 * Entidad de dominio pura: no depende de Laravel ni de ningun pais.
 *
 * @property-read LineaDocumento[] $lineas
 */
final class Documento
{
    private EstadoDocumento $estado = EstadoDocumento::BORRADOR;

    private ?string $referenciaExterna = null;

    private ?string $xmlPath = null;

    private ?string $cdrPath = null;

    /** @param LineaDocumento[] $lineas */
    public function __construct(
        public readonly Pais $pais,
        public readonly TipoDocumento $tipo,
        public readonly Emisor $emisor,
        public readonly Receptor $receptor,
        public readonly array $lineas,
        public readonly string $moneda = 'PEN',
        public readonly ?string $serie = null,
        public readonly ?int $numero = null,
        public readonly array $meta = [], // idempotency_key, origen, etc.
    ) {
    }

    public function subtotal(): float
    {
        return round(array_sum(array_map(fn (LineaDocumento $l) => $l->baseImponible(), $this->lineas)), 2);
    }

    public function impuestos(): float
    {
        return round(array_sum(array_map(fn (LineaDocumento $l) => $l->impuesto(), $this->lineas)), 2);
    }

    public function total(): float
    {
        return round($this->subtotal() + $this->impuestos(), 2);
    }

    public function estado(): EstadoDocumento
    {
        return $this->estado;
    }

    public function marcarEnviado(): void
    {
        $this->estado = EstadoDocumento::ENVIADO;
    }

    public function marcarAceptado(string $referenciaExterna): void
    {
        $this->estado = EstadoDocumento::ACEPTADO;
        $this->referenciaExterna = $referenciaExterna;
    }

    public function marcarRechazado(): void
    {
        $this->estado = EstadoDocumento::RECHAZADO;
    }

    public function referenciaExterna(): ?string
    {
        return $this->referenciaExterna;
    }

    /** Asocia las rutas de los archivos fiscales generados (XML firmado y CDR). */
    public function adjuntarRutas(?string $xmlPath, ?string $cdrPath): void
    {
        $this->xmlPath = $xmlPath;
        $this->cdrPath = $cdrPath;
    }

    public function xmlPath(): ?string
    {
        return $this->xmlPath;
    }

    public function cdrPath(): ?string
    {
        return $this->cdrPath;
    }
}
