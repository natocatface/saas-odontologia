<?php

namespace App\Facturacion\Domain\Enum;

/** Paises soportados (ISO 3166-1 alpha-2). Agregar un valor NO obliga a tocar el dominio. */
enum Pais: string
{
    case PE = 'PE'; // Peru  - SUNAT
    case CO = 'CO'; // Colombia - DIAN
    case CL = 'CL'; // Chile - SII
    case AR = 'AR'; // Argentina - ARCA/AFIP
    case MX = 'MX'; // Mexico - SAT

    public function autoridad(): string
    {
        return match ($this) {
            self::PE => 'SUNAT',
            self::CO => 'DIAN',
            self::CL => 'SII',
            self::AR => 'ARCA',
            self::MX => 'SAT',
        };
    }
}
