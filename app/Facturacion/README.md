# Módulo `App\Facturacion` — Facturación electrónica multipaís

Bounded context desacoplado (Clean Architecture + DDD). El ERP solo habla con la **fachada** `Application\Services\ServicioFacturacion` mediante **DTOs**. Ver diseño completo en `docs/arquitectura-facturacion-electronica.md`.

## Capas
- **Domain/** — núcleo puro (contratos, modelo, enums, resultados, excepciones). No depende de Laravel.
- **Application/** — casos de uso, DTOs, puertos y la fachada.
- **Infrastructure/** — Eloquent, SUNAT, colas, almacenamiento, registro de estrategias.
- **Providers/** — `FacturacionServiceProvider` (bindings).

## Activación
1. Registrar el provider en `bootstrap/providers.php`:
   ```php
   App\Facturacion\Providers\FacturacionServiceProvider::class,
   ```
2. Migrar:
   ```bash
   php artisan migrate
   ```
3. (Opcional) Configurar SUNAT en `.env`:
   ```
   FACT_PAIS=PE
   SUNAT_MODO=demo        # demo | beta | produccion
   ```

## Uso desde el ERP
```php
use App\Facturacion\Application\Services\ServicioFacturacion;
use App\Facturacion\Application\DTO\EmitirFacturaDTO;

$resultado = app(ServicioFacturacion::class)->emitirFactura(new EmitirFacturaDTO(
    empresaId: 1,
    pais: 'PE',
    tipo: 'boleta',
    receptor: ['identificacion' => '12345678', 'tipoIdentificacion' => 'DNI', 'razonSocial' => 'Juan Perez'],
    items: [
        ['descripcion' => 'Limpieza dental', 'cantidad' => 1, 'precio' => 120.00, 'tasa' => 0.18],
    ],
    moneda: 'PEN',
    origen: ['tipo' => 'pago', 'id' => 55],
));

// $resultado->exito, $resultado->estado, $resultado->referenciaExterna
```

Con `SUNAT_MODO=demo` el transmisor acepta y devuelve una referencia simulada, para probar el flujo end-to-end sin SUNAT real.

## Agregar un país nuevo (sin tocar lo existente)
1. `Infrastructure/Providers/{Pais}/` → implementar `ProveedorFacturacion` (+ `GeneradorDocumento`, `FirmanteDigital`, `TransmisorFiscal`).
2. Añadir la línea en `config/facturacion.php > proveedores`.
3. Cablear sus dependencias (contextual binding) en `FacturacionServiceProvider`.

> Estado: **Fase 0 (cimientos)**. La integración real UBL 2.1 + firma XAdES + envío SUNAT/CDR + PDF es la **Fase 1**.
