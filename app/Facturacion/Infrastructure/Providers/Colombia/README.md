# Colombia (DIAN) — pendiente Fase 4

Para agregar Colombia **sin tocar el codigo existente**:

1. Crear en esta carpeta:
   - `DianProveedor.php`  → implements `App\Facturacion\Domain\Contracts\ProveedorFacturacion`
   - `DianGeneradorXml.php` → implements `GeneradorDocumento` (UBL 2.1 DIAN + CUFE)
   - `DianFirmante.php` → implements `FirmanteDigital`
   - `DianTransmisor.php` → implements `TransmisorFiscal` (API DIAN / proveedor tecnologico)
2. Registrar en `config/facturacion.php`:
   ```php
   'proveedores' => [
       'PE' => \App\Facturacion\Infrastructure\Providers\Peru\SunatProveedor::class,
       'CO' => \App\Facturacion\Infrastructure\Providers\Colombia\DianProveedor::class, // <-- nuevo
   ],
   ```
3. Bindear sus dependencias en `FacturacionServiceProvider` (contextual binding para Colombia).

El dominio, la aplicacion y el resto de paises **no se modifican**. Mismo patron para Chile (SII), Argentina (ARCA/AFIP) y Mexico (SAT).
