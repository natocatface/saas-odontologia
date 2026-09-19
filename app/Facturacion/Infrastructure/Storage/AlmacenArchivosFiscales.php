<?php

namespace App\Facturacion\Infrastructure\Storage;

use App\Facturacion\Application\Ports\AlmacenDocumentos;
use Illuminate\Support\Facades\Storage;

/** Almacena XML firmado, CDR y PDF en el disco configurado (public/s3). */
final class AlmacenArchivosFiscales implements AlmacenDocumentos
{
    public function __construct(
        private readonly string $disco = 'local',
    ) {
    }

    public function guardar(string $ruta, string $contenido): string
    {
        Storage::disk($this->disco)->put($ruta, $contenido);

        return $ruta;
    }

    public function url(string $ruta): string
    {
        return Storage::disk($this->disco)->url($ruta);
    }
}
