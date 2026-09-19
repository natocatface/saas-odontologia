<?php

use App\Facturacion\Infrastructure\Providers\Peru\SunatProveedor;

return [

    // Pais por defecto de la instalacion.
    'pais_defecto' => env('FACT_PAIS', 'PE'),

    // Disco donde se guardan XML firmado, CDR y PDF.
    'disco' => env('FACT_DISCO', 'local'),

    /*
     | Mapa de estrategias por pais. AGREGAR UN PAIS = agregar una linea aqui.
     | No se modifica ni el dominio ni la aplicacion.
     */
    'proveedores' => [
        'PE' => SunatProveedor::class,
        // 'CO' => \App\Facturacion\Infrastructure\Providers\Colombia\DianProveedor::class,
        // 'CL' => \App\Facturacion\Infrastructure\Providers\Chile\SiiProveedor::class,
        // 'AR' => \App\Facturacion\Infrastructure\Providers\Argentina\AfipProveedor::class,
        // 'MX' => \App\Facturacion\Infrastructure\Providers\Mexico\SatProveedor::class,
    ],

    // Config especifica de Peru / SUNAT.
    'peru' => [
        'modo' => env('SUNAT_MODO', 'demo'), // demo | beta | produccion
        'endpoint' => env('SUNAT_ENDPOINT'),
        'usuario_sol' => env('SUNAT_USUARIO_SOL'),
        'clave_sol' => env('SUNAT_CLAVE_SOL'),
        'ruta_certificado' => env('SUNAT_CERT_PATH'),
        'clave_certificado' => env('SUNAT_CERT_PASS'),
    ],

];
