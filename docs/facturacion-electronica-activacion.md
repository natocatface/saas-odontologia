# Facturación Electrónica (Perú / SUNAT) — Activación

Módulo de configuración de comprobantes electrónicos, con emisión real a SUNAT vía **Greenter**.

## 1. Migrar la base de datos

```bash
php artisan migrate
```

Crea la tabla `facturacion_configuraciones` (clave-valor, editable desde la UI).
El `FacturacionServiceProvider` ya quedó registrado en `bootstrap/providers.php`.

## 2. Instalar el motor de emisión (Greenter)

```bash
composer require greenter/lite
```

Sin esta librería la app funciona igual: el driver **Greenter** avisa que falta y
puedes usar los drivers **Ninguno** o **Demo** mientras tanto. Nada se rompe.

## 3. Configurar desde la UI

Menú lateral → **Facturación Electrónica** (solo administradores):

- **Estado y modo**: habilitar, emitir automáticamente, *Driver de emisión* (Ninguno / Demo / Greenter) y *Entorno SUNAT* (Beta / Producción).
- **Datos del emisor**: RUC, razón social, dirección, ubigeo, etc.
- **Credenciales SUNAT**: Usuario/Clave SOL y ruta del certificado `.pem`.
- **Probar conexión con SUNAT**: valida RUC, credenciales, certificado y —si el driver es Greenter— que el certificado cargue.

## 4. Pruebas en Beta (homologación)

SUNAT permite probar con:

- RUC `20000000001`
- Usuario/Clave SOL: `MODDATOS`
- Certificado de prueba de Greenter (`.pem`)

Selecciona **Entorno = Beta** y **Driver = Greenter**.

## Drivers

| Driver   | Qué hace                                                        |
|----------|-----------------------------------------------------------------|
| Ninguno  | No emite; el comprobante queda pendiente.                       |
| Demo     | Acepta y devuelve referencia simulada (prueba el flujo interno).|
| Greenter | Emisión real: UBL 2.1 + firma XAdES + envío a billService + CDR.|

## Emisión automática al registrar un pago

Si activas **"Emitir automáticamente al registrar el pago"** (y la facturación está
habilitada con un driver distinto de *Ninguno*), cada pago genera su comprobante:

- **Tipo**: RUC de 11 dígitos en el paciente → **factura** (serie `F001`); si no → **boleta** (serie `B001`).
- **Correlativo**: se asigna automáticamente como el siguiente de la serie.
- **Receptor**: se toma del paciente (documento, nombre, dirección, email).
- **Importe**: el monto del pago se trata como **IGV incluido** (se calcula la base al 18%).
- El puente vive en `App\Services\EmisionComprobantes`; el enganche está en `PagoController@store`
  dentro de un `try/catch`: **un fallo de facturación nunca bloquea el registro del pago**,
  solo se muestra como aviso. Cada comprobante queda en `documentos_fiscales` ligado al pago
  (`origen_tipo='pago'`), con idempotencia para no duplicar.

## Listado de comprobantes

Menú **Facturación Electrónica** → pestaña **Comprobantes**: lista todo lo emitido
(`documentos_fiscales`) con filtros (estado, tipo, búsqueda), tarjetas de resumen
(total / aceptados / rechazados / pendientes) y **descarga de XML firmado y CDR**.
Los archivos se guardan en el disco `FACT_DISCO` (por defecto `local` →
`storage/app/private/facturacion/pe/`) al emitir con Greenter; las rutas quedan en
`documentos_fiscales.xml_path` / `cdr_path`. La pestaña **Configuración** es la pantalla
de setup. El ítem del menú ahora abre el listado (operación diaria).

## Representación impresa (PDF + QR)

Desde el listado, el botón **Ver** abre la representación impresa del comprobante en
una pestaña nueva: encabezado del emisor, recuadro fiscal (RUC + tipo + serie-número),
cliente, detalle de líneas, totales (Op. gravada / IGV / Total), importe **en letras**
y **código QR** con la cadena oficial de SUNAT
(`RUC|Tipo|Serie|Número|IGV|Total|Fecha|TipoDocReceptor|NumReceptor|Hash`). El *hash* se
lee del `ds:DigestValue` del XML firmado guardado. El QR se dibuja en el navegador
(qrcodejs por CDN) y el botón **Imprimir / Guardar PDF** usa el diálogo del navegador
(mismo patrón que el recibo de pagos). Las líneas del comprobante ahora se persisten en
`documento_lineas` al emitir.

## Anulación (comunicación de baja)

En el listado, el botón **Anular** (solo en facturas/notas **aceptadas**) abre un modal
para indicar el motivo y comunica la baja a SUNAT:

- **Greenter**: genera un *Resumen de Anulación* (Voided/RA), lo envía, obtiene un
  **ticket** y consulta el estado con reintentos; al aceptarse, el comprobante pasa a
  **Anulado** y se guarda el CDR de baja (`storage/app/private/facturacion/pe/bajas/`).
  Si SUNAT aún lo procesa, informa el ticket para reintentar más tarde.
- **Demo**: marca el comprobante como Anulado de forma simulada.
- Las **boletas** se anulan por *Resumen Diario* (no cubierto); por eso el botón solo
  aparece en facturas y notas.

Flujo: `ServicioFacturacion::anularDocumento(AnularDocumentoDTO)` → `AnularDocumentoUseCase`
(valida estado, reconstruye datos vía repositorio) → `SunatProveedor::anularDocumento`
(despacha por driver) → `GreenterBaja`.

## Arquitectura

La lógica vive en el bounded context `App\Facturacion` (Clean Architecture, multipaís).
El driver se resuelve en `SunatTransmisor`, que lee la config editable
(`App\Models\FacturacionConfig::parametrosSunat()`) y delega en
`Greenter\GreenterEmisor` cuando corresponde. Añadir otro país = añadir una línea
en `config/facturacion.php`; el dominio no cambia.
