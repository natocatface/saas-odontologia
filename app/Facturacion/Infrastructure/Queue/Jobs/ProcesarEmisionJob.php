<?php

namespace App\Facturacion\Infrastructure\Queue\Jobs;

use App\Facturacion\Application\DTO\EmitirFacturaDTO;
use App\Facturacion\Application\UseCases\EmitirFacturaUseCase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Emision asincrona con reintentos y backoff exponencial.
 * Los errores transitorios reintentan; los de negocio (rechazo) no.
 */
class ProcesarEmisionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** Numero maximo de intentos ante errores transitorios. */
    public int $tries = 5;

    /** Backoff exponencial (segundos): 1m, 5m, 15m, 1h. */
    public array $backoff = [60, 300, 900, 3600];

    public function __construct(
        private readonly EmitirFacturaDTO $dto,
    ) {
    }

    public function handle(EmitirFacturaUseCase $useCase): void
    {
        $useCase->ejecutar($this->dto);
    }

    /** Clave de unicidad para evitar procesar dos veces el mismo comprobante. */
    public function uniqueId(): string
    {
        return $this->dto->idempotencyKey();
    }
}
