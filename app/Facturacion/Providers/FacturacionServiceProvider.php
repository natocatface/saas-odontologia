<?php

namespace App\Facturacion\Providers;

use App\Facturacion\Application\Ports\AlmacenDocumentos;
use App\Facturacion\Application\Ports\RepositorioDocumentos;
use App\Facturacion\Domain\Contracts\FirmanteDigital;
use App\Facturacion\Domain\Contracts\GeneradorDocumento;
use App\Facturacion\Domain\Contracts\TransmisorFiscal;
use App\Facturacion\Infrastructure\Persistence\EloquentRepositorioDocumentos;
use App\Facturacion\Infrastructure\Providers\Peru\SunatFirmante;
use App\Facturacion\Infrastructure\Providers\Peru\SunatGeneradorXml;
use App\Facturacion\Infrastructure\Providers\Peru\SunatProveedor;
use App\Facturacion\Infrastructure\Providers\Peru\SunatTransmisor;
use App\Facturacion\Infrastructure\Registry\RegistroProveedores;
use App\Facturacion\Infrastructure\Storage\AlmacenArchivosFiscales;
use Illuminate\Support\ServiceProvider;

class FacturacionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../../config/facturacion.php', 'facturacion');

        // Puertos -> adaptadores
        $this->app->bind(RepositorioDocumentos::class, EloquentRepositorioDocumentos::class);

        $this->app->bind(AlmacenDocumentos::class, fn () => new AlmacenArchivosFiscales(
            disco: config('facturacion.disco', 'local'),
        ));

        // Registro de estrategias por pais (mapa desde config)
        $this->app->singleton(RegistroProveedores::class, fn ($app) => new RegistroProveedores(
            container: $app,
            mapa: config('facturacion.proveedores', []),
        ));

        // Cableado del proveedor de Peru (contextual: sus dependencias son las de SUNAT)
        $this->app->when(SunatProveedor::class)
            ->needs(GeneradorDocumento::class)->give(SunatGeneradorXml::class);
        $this->app->when(SunatProveedor::class)
            ->needs(FirmanteDigital::class)->give(SunatFirmante::class);
        $this->app->when(SunatProveedor::class)
            ->needs(TransmisorFiscal::class)->give(fn () => new SunatTransmisor(
                // Config editable desde la UI (tabla facturacion_configuraciones),
                // con fallback a los valores por defecto del modelo.
                \App\Models\FacturacionConfig::parametrosSunat(),
            ));
        // El proveedor tambien recibe la config (para la comunicacion de baja).
        $this->app->when(SunatProveedor::class)
            ->needs('$config')->give(fn () => \App\Models\FacturacionConfig::parametrosSunat());
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../../config/facturacion.php' => config_path('facturacion.php'),
        ], 'facturacion-config');
    }
}
