<?php

namespace App\Facturacion\Application\DTO;

/**
 * DTO de entrada desde el ERP. Es la UNICA forma en que el ERP habla con Facturacion.
 * No contiene entidades Eloquent -> mantiene el modulo desacoplado y extraible a microservicio.
 */
final class EmitirFacturaDTO
{
    /**
     * @param array<int, array{descripcion:string, cantidad:float, precio:float, tasa?:float, codigo?:string}> $items
     * @param array{tipo:string, id:int|string} $origen
     */
    public function __construct(
        public readonly int $empresaId,
        public readonly string $pais,          // 'PE', 'CO'...
        public readonly string $tipo,          // 'factura' | 'boleta'
        public readonly array $receptor,       // ['identificacion','tipoIdentificacion','razonSocial',...]
        public readonly array $items,
        public readonly string $moneda = 'PEN',
        public readonly ?string $serie = null,
        public readonly array $origen = [],    // ['tipo'=>'pago','id'=>123]
    ) {
    }

    public function idempotencyKey(): string
    {
        $origen = ($this->origen['tipo'] ?? 'na').'-'.($this->origen['id'] ?? 'na');

        return "{$this->empresaId}:{$this->pais}:{$this->tipo}:{$origen}";
    }
}
