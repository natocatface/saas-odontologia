# Arquitectura — Facturación Electrónica Multipaís (OdontoCRM)

> Documento de arquitectura de software. Rol: arquitecto senior de aplicaciones SaaS multiempresa y facturación electrónica internacional.
> Objetivo: incorporar facturación electrónica empezando por **Perú (SUNAT)** y poder sumar **Colombia (DIAN), Chile (SII), Argentina (ARCA/AFIP), México (SAT)** y otros **sin modificar la lógica del ERP** ni de los países ya implementados.

---

## 1. Principios rectores

- **Clean Architecture / Hexagonal**: el dominio de facturación no conoce Laravel, ni SUNAT, ni HTTP. Las dependencias apuntan hacia adentro (Infraestructura → Aplicación → Dominio).
- **Domain-Driven Design (DDD)**: `Facturacion` es un **bounded context** propio, con su lenguaje ubicuo (Documento, Emisor, Receptor, Serie, Comprobante, Nota de Crédito, CDR).
- **Open/Closed Principle**: agregar un país = **agregar** clases nuevas, nunca **modificar** las existentes.
- **Strategy + Adapter + Registry**: cada país es una *estrategia* intercambiable detrás de una interfaz común; un *registro* resuelve la estrategia por país en tiempo de ejecución.
- **Desacoplamiento del ERP**: el ERP (citas, presupuestos, pagos) solo conoce una **fachada** (`ServicioFacturacion`) y DTOs. Hoy es una llamada en-proceso; mañana puede ser REST o mensajería, **sin tocar el dominio**.

---

## 2. Decisión de despliegue (y evolución)

Se implementa como **módulo/bounded context dentro del Laravel actual** (`app/Facturacion`), pero diseñado como si fuera un servicio independiente:

- Namespace aislado `App\Facturacion\...`, sin dependencias hacia `App\Models\*` del ERP.
- El ERP se comunica **solo** a través de `App\Facturacion\Application\Services\ServicioFacturacion` usando **DTOs** (no entidades Eloquent).
- Frontera lista para extraerse a microservicio: basta reemplazar la fachada por un cliente HTTP y exponer `Infrastructure/Http`. El contrato (interfaces + DTOs) no cambia.

```
Fase actual:   ERP ──(DTO, en-proceso)──▶ ServicioFacturacion ──▶ Proveedor(País)
Fase futura:   ERP ──(REST/Cola)──▶ [Microservicio Facturación] ──▶ Proveedor(País)
```

---

## 3. Contrato común (la clave de la extensibilidad)

Toda autoridad fiscal se modela con **una sola interfaz**. El ERP y la capa de aplicación dependen de esto, nunca de SUNAT/DIAN/etc.

```php
interface ProveedorFacturacion
{
    public function emitirFactura(Documento $documento): ResultadoEmision;
    public function emitirNotaCredito(Documento $documento): ResultadoEmision;
    public function anularDocumento(Documento $documento, string $motivo): ResultadoEmision;
    public function consultarEstado(string $referencia): ResultadoConsulta;
    public function pais(): Pais;               // identifica la estrategia
}
```

Interfaces de soporte (también intercambiables por país), para separar responsabilidades dentro de cada estrategia:

```php
interface GeneradorDocumento { public function generar(Documento $d): string; } // XML (UBL 2.1, DIAN, DTE...)
interface FirmanteDigital   { public function firmar(string $xml): string; }     // firma XAdES / cert por empresa
interface TransmisorFiscal  { public function enviar(string $xmlFirmado, Documento $d): RespuestaFiscal; } // SOAP/REST a la autoridad u OSE
```

**Cómo se agrega un país nuevo (sin tocar nada existente):**
1. Crear carpeta `Infrastructure/Providers/{Pais}/`.
2. Implementar `ProveedorFacturacion` (y sus `GeneradorDocumento`, `FirmanteDigital`, `TransmisorFiscal`).
3. Registrar la clase en `config/facturacion.php` bajo la clave del país.
4. Listo. El `RegistroProveedores` la resuelve por `Pais`; Dominio y Aplicación **no cambian**.

---

## 4. Estructura de carpetas

```
app/Facturacion/
├── Domain/                         # Núcleo puro (sin Laravel)
│   ├── Contracts/
│   │   ├── ProveedorFacturacion.php
│   │   ├── GeneradorDocumento.php
│   │   ├── FirmanteDigital.php
│   │   └── TransmisorFiscal.php
│   ├── Model/                      # Entidades y value objects
│   │   ├── Documento.php           # Aggregate root
│   │   ├── LineaDocumento.php
│   │   ├── Emisor.php
│   │   ├── Receptor.php
│   │   ├── Impuesto.php
│   │   └── Dinero.php              # Value object (monto + moneda)
│   ├── Enum/
│   │   ├── Pais.php                # PE, CO, CL, AR, MX...
│   │   ├── TipoDocumento.php       # FACTURA, BOLETA, NOTA_CREDITO...
│   │   └── EstadoDocumento.php     # BORRADOR, ENVIADO, ACEPTADO, RECHAZADO, ANULADO, ERROR
│   ├── Result/
│   │   ├── ResultadoEmision.php
│   │   ├── ResultadoConsulta.php
│   │   └── RespuestaFiscal.php
│   ├── Event/                      # Eventos de dominio (auditoría)
│   │   ├── DocumentoEmitido.php
│   │   ├── DocumentoRechazado.php
│   │   └── DocumentoAnulado.php
│   └── Exception/
│       ├── FacturacionException.php
│       ├── DocumentoRechazadoException.php
│       └── ProveedorNoConfiguradoException.php
│
├── Application/                    # Casos de uso y orquestación
│   ├── Services/
│   │   └── ServicioFacturacion.php # FACHADA que usa el ERP
│   ├── UseCases/
│   │   ├── EmitirFacturaUseCase.php
│   │   ├── EmitirNotaCreditoUseCase.php
│   │   ├── AnularDocumentoUseCase.php
│   │   └── ConsultarEstadoUseCase.php
│   ├── DTO/
│   │   ├── EmitirFacturaDTO.php
│   │   └── NotaCreditoDTO.php
│   └── Ports/                      # Interfaces que la infraestructura implementa
│       ├── RepositorioDocumentos.php
│       └── AlmacenDocumentos.php
│
├── Infrastructure/                 # Detalles: Laravel, BD, SUNAT, colas
│   ├── Registry/
│   │   └── RegistroProveedores.php # Strategy resolver (país → proveedor)
│   ├── Providers/                  # Una carpeta por país (adaptadores)
│   │   ├── Peru/
│   │   │   ├── SunatProveedor.php
│   │   │   ├── SunatGeneradorXml.php   # UBL 2.1
│   │   │   ├── SunatFirmante.php        # XAdES
│   │   │   └── SunatTransmisor.php      # SOAP/REST SUNAT u OSE
│   │   ├── Colombia/   (DianProveedor.php ...)   ← se agrega en Fase 4
│   │   ├── Chile/      (SiiProveedor.php ...)
│   │   ├── Argentina/  (AfipProveedor.php ...)
│   │   └── Mexico/     (SatProveedor.php ...)
│   ├── Persistence/
│   │   ├── Models/DocumentoFiscal.php  # Eloquent
│   │   └── EloquentRepositorioDocumentos.php
│   ├── Storage/
│   │   └── AlmacenArchivosFiscales.php  # XML/CDR/PDF en disco/S3
│   ├── Queue/Jobs/
│   │   └── ProcesarEmisionJob.php       # asíncrono + reintentos
│   └── Http/                            # (opcional) API REST si se extrae a microservicio
│       └── Controllers/FacturacionController.php
│
└── Providers/
    └── FacturacionServiceProvider.php   # Bindings + registro de proveedores
```

Fuera del módulo:
```
config/facturacion.php                          # país por defecto, mapa país→proveedor, credenciales
database/migrations/..._create_documentos_fiscales.php
```

---

## 5. Modelo de datos

### 5.1 Dominio (en memoria)
- **Documento** (aggregate root): tipo, serie, número, emisor, receptor, líneas, moneda, totales, estado.
- **LineaDocumento**: descripción, cantidad, precio, impuestos por línea.
- **Emisor / Receptor**: identificación fiscal (RUC/NIT/RUT/CUIT/RFC), razón social, dirección.
- **Impuesto**: tipo (IGV/IVA/…), tasa, base, monto.
- **Dinero**: value object inmutable (importe + moneda ISO-4217).

### 5.2 Persistencia (tablas)

`documentos_fiscales`
| Campo | Tipo | Notas |
|---|---|---|
| id | bigint | PK |
| uuid | uuid | id público estable |
| empresa_id | bigint | multiempresa (tenant) |
| pais | string(2) | PE, CO, CL... |
| tipo | string | FACTURA, BOLETA, NOTA_CREDITO |
| serie / numero | string / int | correlativo por serie |
| emisor_snapshot | json | datos del emisor al emitir |
| receptor_snapshot | json | datos del receptor al emitir |
| moneda | string(3) | PEN, USD... |
| subtotal / impuestos / total | decimal(12,2) | |
| estado | string | BORRADOR/ENVIADO/ACEPTADO/RECHAZADO/ANULADO/ERROR |
| referencia_externa | string | ticket/CUFE/folio de la autoridad |
| hash | string | resumen del XML firmado |
| idempotency_key | string, unique | evita duplicados |
| intentos | int | reintentos realizados |
| error | text | último error |
| xml_path / cdr_path / pdf_path | string | rutas en el almacén |
| origen_tipo / origen_id | string / bigint | vínculo suave al ERP (Pago/Presupuesto) |
| created_at / updated_at | timestamp | |

`documento_lineas` (id, documento_id, descripcion, cantidad, precio, impuesto_tasa, impuesto_monto, total)

`documento_eventos` (id, documento_id, tipo, estado_anterior, estado_nuevo, payload json, created_at) → **auditoría inmutable** de transiciones y respuestas de la autoridad.

> **Vínculo con el ERP:** se usa `origen_tipo/origen_id` (referencia suave), no FK duras, para mantener el módulo desacoplado y extraíble a microservicio.

---

## 6. Flujo de emisión (secuencia)

```
1. ERP (al confirmar un Pago/Presupuesto) → ServicioFacturacion::emitirFactura(EmitirFacturaDTO)
2. UseCase construye el Documento (dominio), valida y persiste en estado BORRADOR (idempotency_key)
3. Encola ProcesarEmisionJob (asíncrono) → responde al ERP "en proceso" (no bloquea la UI)
4. Job resuelve el Proveedor por país vía RegistroProveedores
5. Proveedor: GeneradorDocumento (XML) → FirmanteDigital (firma) → TransmisorFiscal (envío)
6. Autoridad responde:
   - ACEPTADO  → guarda CDR/estado, genera PDF (representación impresa), emite DocumentoEmitido
   - RECHAZADO → estado RECHAZADO, guarda motivo, emite DocumentoRechazado (no reintenta)
   - ERROR red/timeout → reintento con backoff (ver §7)
7. Auditoría: cada transición se registra en documento_eventos
```

La **anulación** (comunicación de baja / nota de crédito) y la **consulta de estado** siguen el mismo patrón a través de la misma interfaz.

---

## 7. Manejo de errores, reintentos e idempotencia

- **Clasificación de errores**:
  - *De negocio* (RUC inválido, XML rechazado por la autoridad) → **no se reintenta**; estado `RECHAZADO`, se notifica.
  - *Transitorios* (timeout, 5xx, servicio caído) → **reintento con backoff exponencial** (p. ej. 1m, 5m, 15m, 1h) hasta N intentos; luego cola *dead-letter* + alerta.
- **Idempotencia**: `idempotency_key` (derivada de origen + serie/número) impide duplicar comprobantes ante reintentos o doble clic.
- **Consistencia**: la persistencia del Documento y el encolado ocurren en la misma transacción (outbox-ligero); el envío real es asíncrono.
- **Timeouts y circuit breaker** por proveedor para no arrastrar la caída de una autoridad al resto del sistema.

---

## 8. Auditoría y almacenamiento

- **Auditoría**: tabla `documento_eventos` (append-only) + eventos de dominio (`DocumentoEmitido/Rechazado/Anulado`). Se guarda request/response con la autoridad (payloads) para trazabilidad y contingencia.
- **Almacenamiento de archivos** (`AlmacenArchivosFiscales`): XML firmado, **CDR/constancia**, y **PDF** (representación impresa con QR). Convención de rutas:
  ```
  facturacion/{empresa_id}/{pais}/{aaaa}/{mm}/{tipo}-{serie}-{numero}.{xml|zip|pdf}
  ```
  Disco `public`/`s3` configurable. Retención según normativa (p. ej. SUNAT exige conservar los XML).

---

## 9. Comunicación ERP ↔ Servicio

- **Hoy (en-proceso)**: el ERP llama a `ServicioFacturacion` con un DTO. Simple, transaccional, sin latencia de red.
- **Mañana (microservicio)**: dos opciones, ya soportadas por el diseño:
  - **REST síncrono** para operaciones puntuales (emitir, consultar) + **webhooks** para el resultado asíncrono.
  - **Mensajería** (colas/eventos) para alto volumen y resiliencia (el ERP publica `SolicitudEmision`, el servicio responde `ResultadoEmision`).
- El contrato (interfaces + DTOs) es idéntico en ambos casos; cambia solo la implementación de la fachada.

---

## 10. Multiempresa y seguridad

- **Tenant (`empresa_id`)** en cada documento; **certificados digitales y credenciales por empresa** (no globales), guardados cifrados.
- Series y correlativos **por empresa y por tipo**.
- Secretos fuera del código (variables de entorno / vault). Certificados con acceso restringido.

---

## 11. Hoja de ruta por fases

| Fase | Alcance | Resultado |
|---|---|---|
| **0. Cimientos** | Contratos, dominio, persistencia, fachada in-process, config, migración (este scaffolding). Proveedor "nulo" para pruebas. | Módulo integrable; el ERP ya puede "emitir" contra un stub. |
| **1. Perú/SUNAT** | UBL 2.1, firma XAdES, envío a SUNAT/OSE, CDR, PDF+QR, boleta/factura, nota de crédito, comunicación de baja, consulta. Homologación SUNAT. | Facturación real en Perú. |
| **2. Resiliencia** | Colas, reintentos con backoff, idempotencia, dead-letter, observabilidad (logs/metrics/alertas). | Producción robusta a alto volumen. |
| **3. Microservicio** | Extraer `Infrastructure/Http`, API REST + auth, webhooks; el ERP consume por HTTP. | Servicio independiente reutilizable. |
| **4. Nuevos países** | Colombia (DIAN), Chile (SII), Argentina (AFIP), México (SAT) — **un provider por país**, sin tocar lo previo. | Cobertura internacional. |
| **5. Producto** | Multiempresa avanzada, panel de comprobantes, reportes fiscales, contingencia. | SaaS fiscal completo. |

---

## 12. Integración con el ERP dental (punto de enganche)

El disparador natural es el **Pago** o el **Presupuesto aprobado**. Ejemplo de uso desde el ERP:

```php
$servicio->emitirFactura(new EmitirFacturaDTO(
    empresaId: $clinica->id,
    pais: 'PE',
    receptor: [...],      // datos del paciente/cliente
    items: [...],         // desde los items del presupuesto/pago
    moneda: 'PEN',
    origen: ['tipo' => 'pago', 'id' => $pago->id],
));
```

El ERP no sabe nada de SUNAT: solo arma el DTO y recibe un `ResultadoEmision`. Cuando se sume Colombia, el mismo código funciona cambiando `pais => 'CO'`.

---

## Anexo — Ubicación del código generado

El scaffolding vive en `app/Facturacion/` (namespace `App\Facturacion`). Para activarlo, registra el service provider en `bootstrap/providers.php`:

```php
return [
    App\Providers\AppServiceProvider::class,
    App\Facturacion\Providers\FacturacionServiceProvider::class, // <-- agregar
];
```

y ejecuta la migración de `documentos_fiscales` (`php artisan migrate`).
