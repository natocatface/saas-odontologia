# OdontoCRM — SaaS para Odontología

Sistema de gestión para clínicas dentales construido con **Laravel 11**, **MySQL** y **TailwindCSS**.

Esta primera entrega incluye: **login**, **dashboard** con estadísticas reales, **menú lateral (sidebar)** con todos los módulos del sistema, **cambio de contraseña** y **pantallas placeholder navegables** para cada módulo.

---

## Requisitos

- PHP 8.2 o superior
- Composer
- MySQL 5.7+ / MariaDB (servidor en `localhost:3306`, usuario `root`, sin contraseña)

---

## Instalación (paso a paso)

Desde la carpeta del proyecto (`C:\SAAS\saas_odontologia`), abre una terminal y ejecuta:

```bash
# 1. Instalar dependencias de Laravel
composer install

# 2. Generar la clave de aplicación (el archivo .env ya está creado y configurado)
php artisan key:generate

# 3. Crear la base de datos en MySQL
#    Opción A — desde la terminal:
mysql -u root -e "CREATE DATABASE saas_odontologia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
#    Opción B — desde phpMyAdmin / HeidiSQL: crear una BD llamada "saas_odontologia"

# 4. Ejecutar migraciones y cargar datos de demostración
php artisan migrate --seed

# 5. Levantar el servidor
php artisan serve
```

Luego abre en el navegador: **http://localhost:8000**

> El archivo `.env` ya viene configurado con `DB_DATABASE=saas_odontologia`, `DB_USERNAME=root` y `DB_PASSWORD=` (vacío). Si tu MySQL tiene contraseña, edítala en `.env` (`DB_PASSWORD=tu_clave`).

---

## Credenciales de acceso (datos demo)

| Rol | Correo | Contraseña |
|-----|--------|------------|
| Administrador | `admin@odontocrm.com` | `password` |
| Doctor | `doctor1@odontocrm.com` | `password` |
| Recepción | `recepcion@odontocrm.com` | `password` |

El seeder crea además **6 doctores**, **80 pacientes** y **40 citas** de ejemplo para que el dashboard muestre datos reales.

---

## Módulos del sistema (menú lateral)

- **Dashboard** — resumen general con gráficos (✅ implementado)
- **Pacientes** — ficha, historia clínica y CRUD completo (✅ implementado)
- **Citas** — agenda con filtros y cambio de estado (✅ implementado)
- **Tratamientos** — catálogo con precios y categorías (✅ implementado)
- **Usuarios** — administración de usuarios y roles, solo admin (✅ implementado)
- **Presupuestos** — cotizaciones con líneas de detalle y estados (✅ implementado)
- **Pagos** — registro de cobros, ingresos y saldo de presupuestos (✅ implementado)
- **Reportes** — KPIs y gráficas con filtro de fechas (✅ implementado)
- **Actividad** — bitácora de auditoría automática, solo admin (✅ implementado)
- **Mantenimiento** — info del sistema, respaldo SQL y limpieza de caché, solo admin (✅ implementado)
- **Configuración** — datos de la clínica y parámetros, solo admin (✅ implementado)

Los 10 módulos están implementados y enlazados en el menú. Los módulos administrativos (Usuarios, Configuración, Actividad, Mantenimiento) solo son visibles para el rol `admin`.

> **Importante:** tras esta versión hay tablas nuevas (tratamientos, presupuestos, presupuesto_items, pagos, configuraciones, actividades). Ejecuta `php artisan migrate` y carga el catálogo de ejemplo con `php artisan db:seed --class=TratamientoSeeder`.

---

## Estructura relevante

```
app/
  Http/Controllers/      → Auth, Dashboard, Module
  Http/Middleware/        → EnsureUserRole (control por rol)
  Models/                 → User, Paciente, Cita
database/
  migrations/             → users, pacientes, citas, cache, jobs
  seeders/DatabaseSeeder  → admin, doctores, pacientes, citas demo
resources/views/
  auth/login.blade.php    → pantalla de login
  layouts/app.blade.php   → layout con sidebar + topbar
  dashboard.blade.php     → tablero principal
  modules/placeholder     → pantallas de módulos
routes/web.php            → rutas
```

## Notas técnicas

- El frontend usa **TailwindCSS** y **Alpine.js** vía CDN, por lo que no se requiere `npm install` ni compilación de assets para arrancar.
- El sidebar es colapsable (botón "Colapsar") y responsive (menú hamburguesa en móvil).
- Las fechas se muestran en español (locale `es`).
- Roles disponibles: `admin`, `doctor`, `recepcion`. El middleware `role:` permite restringir rutas por rol cuando se implementen los CRUD.

---

## Próximos pasos sugeridos

1. CRUD de **Pacientes** con historia clínica y odontograma.
2. **Citas** con calendario interactivo y estados.
3. Módulos de **Tratamientos**, **Presupuestos** y **Pagos** (incluye cálculo de ingresos del mes en el dashboard).
4. **Reportes** con gráficas.
5. Gestión de **Usuarios y permisos**.
