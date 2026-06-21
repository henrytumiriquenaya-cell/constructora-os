```
   ┌────────┐  ┌────────┐  ┌────────┐  ┌────────┐  ┌────────┐
   │ ▓▓▓▓▓▓ │  │ ▓▓▓▓▓▓ │  │ ▓▓▓▓▓▓ │  │ ▓▓▓▓▓▓ │  │ ▓▓▓▓▓▓ │
   │ ▓▓▓▓▓▓ │  │ ▓▓▓▓▓▓ │  │ ▓▓▓▓▓▓ │  │ ▓▓▓▓▓▓ │  │ ▓▓▓▓▓▓ │
   └───┬────┘  └───┬────┘  └───┬────┘  └───┬────┘  └───┬────┘
       └───────────┴───────────┼───────────┴───────────┘
                          ▓▓▓▓▓▓▓▓▓▓▓
                          ▓ CONSTRUCTORA OS ▓
                          ▓▓▓▓▓▓▓▓▓▓▓
            One sistema · todas las obras · un solo dato real
```

<p align="center"><b>Gestión integral de constructoras</b> · contratos · cuotas · inventario · maquinaria · RRHH · auditoría</p>

<p align="center">
  <img alt="build" src="https://img.shields.io/badge/build-passing-brightgreen">
  <img alt="laravel" src="https://img.shields.io/badge/laravel-10.x-FF2D20">
  <img alt="db" src="https://img.shields.io/badge/mysql-8.0-4479A1">
  <img alt="license" src="https://img.shields.io/badge/license-pendiente-lightgrey">
  <img alt="status" src="https://img.shields.io/badge/estado-en%20desarrollo-blue">
</p>

<p align="center">
  <a href="#demo">Demo</a> ·
  <a href="#descripción-general">Descripción</a> ·
  <a href="#módulos-del-sistema">Módulos</a> ·
  <a href="#stack-tecnológico">Stack</a> ·
  <a href="#instalación">Instalación</a> ·
  <a href="#arquitectura">Arquitectura</a> ·
  <a href="#flujo-de-trabajo--git">Git workflow</a>
</p>

---

Constructora OS reemplaza el manejo disperso de información de una constructora —hojas de cálculo, registros en papel, WhatsApp— por **un único sistema donde administración, obra, logística y RRHH leen y escriben sobre los mismos datos**, en tiempo real, con trazabilidad completa de cada cambio.

```
  cliente · contrato · cotización
                │
                ▼
  ┌────────────────────────────────────────────────────────┐
  │              CONSTRUCTORA OS                           │
  │  ────────────────────────────────────────────────────  │
  │   Proyectos ──▶ Cuotas de pago ──▶ Alertas            │
  │       │                                                │
  │       ├─▶ Asig. personal / maquinaria                  │
  │       ├─▶ Paralizaciones / reanudación                 │
  │       └─▶ Avance %                                     │
  │                                                         │
  │   Compras (almacén central)                             │
  │       └─▶ Inventario ──▶ Uso de material ──▶ Proyecto │
  │                                                    c    │
  │   Triggers MySQL: totales · stock · estados             │
  │   AuditObserver: registro de cambios                    │
  └─────────────────────────────────────────────────────────┘
                │
                ▼
   reportes de costos · log de auditoría · planillas
```

## Demo

<p align="center">
  <img src="public/images/clideo_editor_389c21affe504b04a80cc22af242da02.gif" alt="Demostración de mi página web" width="820">
  <br/><sub>1. Recorrido general de la interfaz — navegación entre módulos, panel de control y flujo de uso diario del sistema.</sub>
</p>

<p align="center">
  <img src="public/images/RolesOficial.gif" alt="Demostración de mi página web" width="820">
  <br/><sub>2. Gestión de roles y permisos — cómo cambia la interfaz y el acceso a los módulos según el rol del usuario autenticado.</sub>
</p>

## Descripción general

El sistema cubre el ciclo de vida completo de un proyecto de construcción:

1. **Captación y contratación** — clientes, cotizaciones y contratos.
2. **Ejecución del proyecto** — avance, asignación de personal y maquinaria, paralizaciones.
3. **Logística y materiales** — compras a nivel de almacén central, inventario y trazabilidad de uso por proyecto mediante movimientos de entrada/salida.
4. **Finanzas del proyecto** — cuotas de pago, vencimientos, mora y reanudación de obra tras regularización.
5. **Recursos humanos** — empleados, asignaciones a obra, control de horas, planillas, permisos.
6. **Reportes y auditoría** — resumen de costos, alertas operativas y log de auditoría sobre los modelos críticos.

## Por qué existe

| Antes | Con Constructora OS |
|---|---|
| Inventario por proyecto, duplicado y desincronizado | Almacén central único; el material se asigna al proyecto recién al usarse |
| Totales y estados calculados a mano en distintos lugares | Triggers de MySQL como única fuente de verdad para esos cálculos |
| Sin registro de quién cambió qué | Auditoría automática vía Observers de Eloquent |
| Acceso uniforme sin distinción de rol | Permisos por rol: admin, gerente, contab, jefe de obra, logística, RRHH, cliente |

## Módulos del sistema

`Maestros` Ciudades · Catálogo de maquinaria · Materiales
`Gestión operativa` Clientes · Contratos · Proyectos · Cotizaciones · Cuotas de pago · Compras · Inventario · Uso de material · Movimientos · Paralizaciones · Obras terminadas
`RRHH` Empleados · Asignación de personal · Control de horas · Asignación de maquinaria · Pagos/planillas · Permisos y trámites
`Reportes` Resumen de costos · Alertas · Log de auditoría
`Configuración` Usuarios · Feriados

## Stack tecnológico

| Capa | Tecnología |
|---|---|
| Backend | Laravel (PHP) |
| Base de datos | MySQL — triggers y procedimientos para lógica de negocio crítica |
| Frontend | Blade · Bootstrap 5 · Tailwind CSS |
| Build tool | Vite |
| Control de versiones | Git / GitHub |

## Instalación

```bash
git clone <url-del-repositorio>
cd constructora-os

composer install
npm install

cp .env.example .env
php artisan key:generate
# Configura DB_DATABASE=empresa_constructora5, DB_USERNAME, DB_PASSWORD en .env

php artisan migrate     # incluye triggers y procedimientos
php artisan db:seed     # opcional

npm run dev              # desarrollo
npm run build            # producción

php artisan serve
```

**Requisitos:** PHP ≥ 8.2 · Composer · Node.js ≥ 20.19 + npm · MySQL ≥ 8.0

## Arquitectura

> **Principio rector:** la lógica de negocio crítica vive en **triggers de MySQL** (`trg_detalle_compra_after_insert`, `trg_movimiento_inventario_after_insert`, `trg_cuota_estado_al_pagar`, entre otros). Los controladores de Laravel **no la duplican** — insertan/actualizan de forma que esos triggers se disparen correctamente.

- **Eloquent vs. `DB::table()`** — los Observers de auditoría solo se disparan con Eloquent. Cualquier módulo que necesite quedar auditado debe usar modelos Eloquent, no query builder puro.
- **Doble registro de auditoría** — todo modelo auditable debe estar tanto en `AppServiceProvider` (`$tablasExcluidas`) como en `AuditObserver` (`$tablasAuditadas`). Falta uno de los dos y el log de auditoría queda silenciosamente roto.
- **Compras a nivel de almacén central** — las órdenes de compra no llevan `id_proyecto`; el material entra al inventario general y se asigna a un proyecto recién al registrarse su uso (`uso_material`, movimiento de salida).

## Flujo de trabajo / Git

- `develop` — rama de integración del equipo. `main` — solo releases estables.
- Ramas con prefijo según naturaleza: `feature/...` para funcionalidad nueva, `fix/...` para correcciones.
- PRs siempre apuntan a `develop`, nunca directo a `main`.
- Commits separados por módulo/responsabilidad, formato `feat(modulo): ...` / `fix(modulo): ...`.

## Equipo

Desarrollado por el equipo de Constructora OS.

## Licencia

_Pendiente de definir._
