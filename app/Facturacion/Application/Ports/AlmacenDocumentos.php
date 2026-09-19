<?php

namespace App\Facturacion\Application\Ports;

/** Puerto de almacenamiento de archivos fiscales (XML firmado, CDR, PDF). */
interface AlmacenDocumentos
{
    /** Guarda un contenido y devuelve la ruta relativa. */
    public function guardar(string $ruta, string $contenido): string;

    public function url(string $ruta): string;
}
