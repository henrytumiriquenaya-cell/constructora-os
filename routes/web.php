<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\AlertasController;
use App\Http\Controllers\GestionOperativa\AsignacionMaquinariaController;
use App\Http\Controllers\GestionOperativa\CiudadController;
use App\Http\Controllers\GestionOperativa\CompraController;
use App\Http\Controllers\GestionOperativa\ContratoController;
use App\Http\Controllers\GestionOperativa\CotizacionController;
use App\Http\Controllers\GestionOperativa\CuotasPagoController;
use App\Http\Controllers\GestionOperativa\InventarioController;
use App\Http\Controllers\GestionOperativa\MaquinariaController;
use App\Http\Controllers\GestionOperativa\MaterialController;
use App\Http\Controllers\GestionOperativa\MovimientoInventarioController;
use App\Http\Controllers\GestionOperativa\ProveedorController;
use App\Http\Controllers\RRHH\EmpleadoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RRHH\FeriadoController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('gestion-operativa')->group(function () {
        Route::get('/clientes', [\App\Http\Controllers\GestionOperativa\ClienteController::class, 'index'])->middleware('permission:cliente')->name('operativa.clientes.index');
        Route::get('/clientes/create', [\App\Http\Controllers\GestionOperativa\ClienteController::class, 'create'])->middleware('permission:cliente')->name('operativa.clientes.create');
        Route::post('/clientes', [\App\Http\Controllers\GestionOperativa\ClienteController::class, 'store'])->middleware('permission:cliente')->name('operativa.clientes.store');
        Route::get('/clientes/{id}', [\App\Http\Controllers\GestionOperativa\ClienteController::class, 'show'])->middleware('permission:cliente')->name('operativa.clientes.show');
        Route::get('/clientes/{id}/edit', [\App\Http\Controllers\GestionOperativa\ClienteController::class, 'edit'])->middleware('permission:cliente')->name('operativa.clientes.edit');
        Route::put('/clientes/{id}', [\App\Http\Controllers\GestionOperativa\ClienteController::class, 'update'])->middleware('permission:cliente')->name('operativa.clientes.update');
        Route::delete('/clientes/{id}', [\App\Http\Controllers\GestionOperativa\ClienteController::class, 'destroy'])->middleware('permission:cliente')->name('operativa.clientes.destroy');

        Route::get('/proyectos', [\App\Http\Controllers\GestionOperativa\ProyectoController::class, 'index'])->middleware('permission:proyecto,S')->name('operativa.proyectos.index');
        Route::get('/proyectos/create', [\App\Http\Controllers\GestionOperativa\ProyectoController::class, 'create'])->middleware('permission:proyecto,I')->name('operativa.proyectos.create');
        Route::post('/proyectos', [\App\Http\Controllers\GestionOperativa\ProyectoController::class, 'store'])->middleware('permission:proyecto,I')->name('operativa.proyectos.store');
        Route::get('/proyectos/{id}', [\App\Http\Controllers\GestionOperativa\ProyectoController::class, 'show'])->middleware('permission:proyecto')->name('operativa.proyectos.show');
        Route::get('/proyectos/{id}/edit', [\App\Http\Controllers\GestionOperativa\ProyectoController::class, 'edit'])->middleware('permission:proyecto,U')->name('operativa.proyectos.edit');
        Route::put('/proyectos/{id}', [\App\Http\Controllers\GestionOperativa\ProyectoController::class, 'update'])->middleware('permission:proyecto,U')->name('operativa.proyectos.update');
        Route::delete('/proyectos/{id}', [\App\Http\Controllers\GestionOperativa\ProyectoController::class, 'destroy'])->middleware('permission:proyecto,D')->name('operativa.proyectos.destroy');

        Route::get('/registro_horas_diaria', [\App\Http\Controllers\GestionOperativa\RegistroHoraController::class, 'index'])
            ->middleware('permission:registro_horas')->name('operativa.asistencia.index');

        Route::get('/registro_horas_diaria/create', [\App\Http\Controllers\GestionOperativa\RegistroHoraController::class, 'create'])
            ->middleware('permission:registro_horas')->name('operativa.asistencia.create');

        Route::get('/paralizacion_obra', [\App\Http\Controllers\GestionOperativa\ParalizacionController::class, 'index'])->middleware('permission:paralizacion')->name('operativa.paralizaciones.index');
        Route::get('/paralizacion_obra/create', [\App\Http\Controllers\GestionOperativa\ParalizacionController::class, 'create'])->middleware('permission:paralizacion')->name('operativa.paralizaciones.create');
        Route::post('/paralizacion_obra', [\App\Http\Controllers\GestionOperativa\ParalizacionController::class, 'store'])->middleware('permission:paralizacion')->name('operativa.paralizaciones.store');
        Route::get('/paralizacion_obra/{id}/edit', [\App\Http\Controllers\GestionOperativa\ParalizacionController::class, 'edit'])->middleware('permission:paralizacion')->name('operativa.paralizaciones.edit');
        Route::put('/paralizacion_obra/{id}', [\App\Http\Controllers\GestionOperativa\ParalizacionController::class, 'update'])->middleware('permission:paralizacion')->name('operativa.paralizaciones.update');
        Route::delete('/paralizacion_obra/{id}', [\App\Http\Controllers\GestionOperativa\ParalizacionController::class, 'destroy'])->middleware('permission:paralizacion')->name('operativa.paralizaciones.destroy');

        Route::get('/obras_terminadas', [\App\Http\Controllers\GestionOperativa\ObraTerminadaController::class, 'index'])
            ->middleware('permission:obras_terminadas')->name('operativa.finalizadas.index');

        Route::get('/ciudades', [CiudadController::class, 'index'])->middleware('permission:ciudad')->name('operativa.ciudades.index');
        Route::get('/ciudades/create', [CiudadController::class, 'create'])->middleware('permission:ciudad')->name('operativa.ciudades.create');
        Route::post('/ciudades', [CiudadController::class, 'store'])->middleware('permission:ciudad')->name('operativa.ciudades.store');
        Route::get('/ciudades/{id}/edit', [CiudadController::class, 'edit'])->middleware('permission:ciudad')->name('operativa.ciudades.edit');
        Route::put('/ciudades/{id}', [CiudadController::class, 'update'])->middleware('permission:ciudad')->name('operativa.ciudades.update');
        Route::delete('/ciudades/{id}', [CiudadController::class, 'destroy'])->middleware('permission:ciudad')->name('operativa.ciudades.destroy');

        Route::get('/contratos', [ContratoController::class, 'index'])->middleware('permission:contrato,S')->name('operativa.contratos.index');
        Route::get('/contratos/create', [ContratoController::class, 'create'])->middleware('permission:contrato,I')->name('operativa.contratos.create');
        Route::post('/contratos', [ContratoController::class, 'store'])->middleware('permission:contrato,U')->name('operativa.contratos.store');
        Route::get('/contratos/{id}', [ContratoController::class, 'show'])->middleware('permission:contrato,S')->name('operativa.contratos.show');
        Route::get('/contratos/{id}/edit', [ContratoController::class, 'edit'])->middleware('permission:contrato,U')->name('operativa.contratos.edit');
        Route::put('/contratos/{id}', [ContratoController::class, 'update'])->middleware('permission:contrato,U')->name('operativa.contratos.update');
        Route::delete('/contratos/{id}', [ContratoController::class, 'destroy'])->middleware('permission:contrato,D')->name('operativa.contratos.destroy');

        Route::get('/compras', [CompraController::class, 'index'])->middleware('permission:compra')->name('operativa.compras.index');
        Route::get('/compras/create', [CompraController::class, 'create'])->middleware('permission:compra')->name('operativa.compras.create');
        Route::post('/compras', [CompraController::class, 'store'])->middleware('permission:compra')->name('operativa.compras.store');
        Route::get('/compras/{id}', [CompraController::class, 'show'])->middleware('permission:compra')->name('operativa.compras.show');
        Route::get('/compras/{id}/detalle', [CompraController::class, 'detalle'])->middleware('permission:compra')->name('operativa.compras.detalle');
        Route::post('/compras/{id}/detalle', [CompraController::class, 'storeDetalle'])->middleware('permission:compra')->name('operativa.compras.detalle.store');
        Route::put('/compras/{id}/detalle', [CompraController::class, 'updateDetalleLote'])->middleware('permission:compra')->name('operativa.compras.detalle.update');
        Route::delete('/compras/{id}/detalle/{detalleId}', [CompraController::class, 'destroyDetalle'])->middleware('permission:compra')->name('operativa.compras.detalle.destroy');
        Route::post('/compras/{id}/detalle/recibir-todo', [CompraController::class, 'recibirTodo'])->middleware('permission:compra')->name('operativa.compras.detalle.recibir_todo');
        Route::get('/compras/{id}/edit', [CompraController::class, 'edit'])->middleware('permission:compra')->name('operativa.compras.edit');
        Route::put('/compras/{id}', [CompraController::class, 'update'])->middleware('permission:compra')->name('operativa.compras.update');
        Route::delete('/compras/{id}', [CompraController::class, 'destroy'])->middleware('permission:compra')->name('operativa.compras.destroy');

        Route::get('/cotizaciones', [CotizacionController::class, 'index'])->middleware('permission:cotizacion')->name('operativa.cotizaciones.index');
        Route::get('/cotizaciones/create', [CotizacionController::class, 'create'])->middleware('permission:cotizacion')->name('operativa.cotizaciones.create');
        Route::post('/cotizaciones', [CotizacionController::class, 'store'])->middleware('permission:cotizacion')->name('operativa.cotizaciones.store');
        Route::get('/cotizaciones/{id}/edit', [CotizacionController::class, 'edit'])->middleware('permission:cotizacion')->name('operativa.cotizaciones.edit');
        Route::put('/cotizaciones/{id}', [CotizacionController::class, 'update'])->middleware('permission:cotizacion')->name('operativa.cotizaciones.update');
        Route::delete('/cotizaciones/{id}', [CotizacionController::class, 'destroy'])->middleware('permission:cotizacion')->name('operativa.cotizaciones.destroy');

        Route::get('/cuotas', [CuotasPagoController::class, 'index'])->middleware('permission:cuotas_pago,S')->name('operativa.cuotas.index');
        Route::get('/cuotas/create', [CuotasPagoController::class, 'create'])->middleware('permission:cuotas_pago,I')->name('operativa.cuotas.create');
        Route::post('/cuotas', [CuotasPagoController::class, 'store'])->middleware('permission:cuotas_pago,U')->name('operativa.cuotas.store');
        Route::post('/cuotas/{id}/registrar-pago', [CuotasPagoController::class, 'registrarPago'])->middleware('permission:cuotas_pago')->name('operativa.cuotas.registrar_pago');
        Route::post('/cuotas/{id}/reanudar-obra', [CuotasPagoController::class, 'reanudarObra'])->middleware('permission:cuotas_pago')->name('operativa.cuotas.reanudar_obra');
        Route::get('/cuotas/{id}/edit', [CuotasPagoController::class, 'edit'])->middleware('permission:cuotas_pago,U')->name('operativa.cuotas.edit');
        Route::put('/cuotas/{id}', [CuotasPagoController::class, 'update'])->middleware('permission:cuotas_pago,U')->name('operativa.cuotas.update');
        Route::delete('/cuotas/{id}', [CuotasPagoController::class, 'destroy'])->middleware('permission:cuotas_pago,D')->name('operativa.cuotas.destroy');

        Route::get('/cuotas/{id}/registrar-pago', [CuotasPagoController::class, 'registrarPagoForm'])
            ->middleware('permission:cuotas_pago')->name('operativa.cuotas.registrar_pago_form');

        Route::get('/inventario', [InventarioController::class, 'index'])->middleware('permission:inventario')->name('operativa.inventario.index');
        Route::get('/inventario/uso', [InventarioController::class, 'create'])->middleware('permission:uso_material')->name('operativa.inventario.uso.create');
        Route::post('/inventario/uso', [InventarioController::class, 'registrarUso'])->middleware('permission:uso_material')->name('operativa.inventario.uso.store');

        Route::get('/catalogo-maquinaria', [MaquinariaController::class, 'index'])->middleware('permission:maquinaria')->name('operativa.maquinarias.catalogo');
        Route::get('/catalogo-maquinaria/create', [MaquinariaController::class, 'create'])->middleware('permission:maquinaria')->name('operativa.maquinarias.catalogo_create');
        Route::post('/catalogo-maquinaria', [MaquinariaController::class, 'store'])->middleware('permission:maquinaria')->name('operativa.maquinarias.catalogo_store');
        Route::get('/catalogo-maquinaria/{id}/edit', [MaquinariaController::class, 'edit'])->middleware('permission:maquinaria')->name('operativa.maquinarias.catalogo_edit');
        Route::put('/catalogo-maquinaria/{id}', [MaquinariaController::class, 'update'])->middleware('permission:maquinaria')->name('operativa.maquinarias.catalogo_update');
        Route::delete('/catalogo-maquinaria/{id}', [MaquinariaController::class, 'destroy'])->middleware('permission:maquinaria')->name('operativa.maquinarias.catalogo_destroy');

        // Ruta para el catálogo de maquinaria (flota)
        Route::get('/maquinarias', [MaquinariaController::class, 'index'])->middleware('permission:maquinaria')->name('operativa.maquinarias.index');

        // Rutas para asignación de maquinaria
        Route::get('/asignaciones', [AsignacionMaquinariaController::class, 'index'])->middleware('permission:asignacion_maquinaria')->name('operativa.maquinarias.asignaciones');
        Route::get('/asignaciones/create', [AsignacionMaquinariaController::class, 'create'])->middleware('permission:asignacion_maquinaria')->name('operativa.maquinarias.asignaciones_create');
        Route::post('/asignaciones', [AsignacionMaquinariaController::class, 'store'])->middleware('permission:asignacion_maquinaria')->name('operativa.maquinarias.asignaciones_store');
        Route::get('/asignaciones/{id}/edit', [AsignacionMaquinariaController::class, 'edit'])->middleware('permission:asignacion_maquinaria')->name('operativa.maquinarias.asignaciones_edit');
        Route::put('/asignaciones/{id}', [AsignacionMaquinariaController::class, 'update'])->middleware('permission:asignacion_maquinaria')->name('operativa.maquinarias.asignaciones_update');
        Route::delete('/asignaciones/{id}', [AsignacionMaquinariaController::class, 'destroy'])->middleware('permission:asignacion_maquinaria')->name('operativa.maquinarias.asignaciones_destroy');

        Route::get('/proveedores', [ProveedorController::class, 'index'])->middleware('permission:proveedor')->name('operativa.proveedores.index');
        Route::get('/proveedores/create', [ProveedorController::class, 'create'])->middleware('permission:proveedor')->name('operativa.proveedores.create');
        Route::post('/proveedores', [ProveedorController::class, 'store'])->middleware('permission:proveedor')->name('operativa.proveedores.store');
        Route::get('/proveedores/{id}/edit', [ProveedorController::class, 'edit'])->middleware('permission:proveedor')->name('operativa.proveedores.edit');
        Route::put('/proveedores/{id}', [ProveedorController::class, 'update'])->middleware('permission:proveedor')->name('operativa.proveedores.update');
        Route::delete('/proveedores/{id}', [ProveedorController::class, 'destroy'])->middleware('permission:proveedor')->name('operativa.proveedores.destroy');

        // ── Materiales CRUD ──────────────────────────────────────────────────
        Route::get('/materiales', [MaterialController::class, 'index'])->middleware('permission:material')->name('operativa.materiales.index');
        Route::get('/materiales/create', [MaterialController::class, 'create'])->middleware('permission:material')->name('operativa.materiales.create');
        Route::post('/materiales', [MaterialController::class, 'store'])->middleware('permission:material')->name('operativa.materiales.store');
        Route::get('/materiales/{id}/edit', [MaterialController::class, 'edit'])->middleware('permission:material')->name('operativa.materiales.edit');
        Route::put('/materiales/{id}', [MaterialController::class, 'update'])->middleware('permission:material')->name('operativa.materiales.update');
        Route::delete('/materiales/{id}', [MaterialController::class, 'destroy'])->middleware('permission:material')->name('operativa.materiales.destroy');

        // ── Movimientos de Inventario CRUD ───────────────────────────────────
        Route::post('/inventario/recalcular', [InventarioController::class, 'recalcular'])
            ->name('operativa.inventario.recalcular');
        
        // Las rutas existentes de movimientos (ya deberías tenerlas, solo verificar):
        Route::get('/movimientos',                [MovimientoInventarioController::class, 'index'])  ->name('operativa.movimientos.index');
        Route::get('/movimientos/create',         [MovimientoInventarioController::class, 'create']) ->name('operativa.movimientos.create');
        Route::post('/movimientos',               [MovimientoInventarioController::class, 'store'])  ->name('operativa.movimientos.store');
        Route::get('/movimientos/{id}/edit',      [MovimientoInventarioController::class, 'edit'])   ->name('operativa.movimientos.edit');
        Route::put('/movimientos/{id}',           [MovimientoInventarioController::class, 'update']) ->name('operativa.movimientos.update');
        Route::delete('/movimientos/{id}',        [MovimientoInventarioController::class, 'destroy'])->name('operativa.movimientos.destroy');
            });

    Route::prefix('recursos-humanos')->group(function () {
        Route::get('/empleados', [EmpleadoController::class, 'index'])->middleware('permission:empleado')->name('rrhh.empleados.index');
        Route::get('/empleados/create', [EmpleadoController::class, 'create'])->middleware('permission:empleado')->name('rrhh.empleados.create');
        Route::post('/empleados', [EmpleadoController::class, 'store'])->middleware('permission:empleado')->name('rrhh.empleados.store');
        Route::get('/empleados/{id}/edit', [EmpleadoController::class, 'edit'])->middleware('permission:empleado')->name('rrhh.empleados.edit');
        Route::put('/empleados/{id}', [EmpleadoController::class, 'update'])->middleware('permission:empleado')->name('rrhh.empleados.update');
        Route::delete('/empleados/{id}', [EmpleadoController::class, 'destroy'])->middleware('permission:empleado')->name('rrhh.empleados.destroy');

        Route::get('/asignaciones', [EmpleadoController::class, 'asignaciones'])
            ->middleware('permission:asignacion_empleado')->name('rrhh.asignaciones.index');

        Route::get('/pagos', [EmpleadoController::class, 'pagos'])
            ->middleware('permission:pago_empleado')->name('rrhh.pagos.index');

        Route::get('/permisos', [\App\Http\Controllers\RRHH\PermisoController::class, 'index'])->middleware('permission:permiso')->name('rrhh.permisos.index');
        Route::get('/permisos/create', [\App\Http\Controllers\RRHH\PermisoController::class, 'create'])->middleware('permission:permiso')->name('rrhh.permisos.create');
        Route::post('/permisos', [\App\Http\Controllers\RRHH\PermisoController::class, 'store'])->middleware('permission:permiso')->name('rrhh.permisos.store');
        Route::get('/permisos/{id}/edit', [\App\Http\Controllers\RRHH\PermisoController::class, 'edit'])->middleware('permission:permiso')->name('rrhh.permisos.edit');
        Route::put('/permisos/{id}', [\App\Http\Controllers\RRHH\PermisoController::class, 'update'])->middleware('permission:permiso')->name('rrhh.permisos.update');
        Route::delete('/permisos/{id}', [\App\Http\Controllers\RRHH\PermisoController::class, 'destroy'])->middleware('permission:permiso')->name('rrhh.permisos.destroy');

        Route::get('/feriados', [FeriadoController::class, 'index'])
            ->middleware('permission:feriado')->name('rrhh.feriados.index');
        Route::get('/feriados/create', [FeriadoController::class, 'create'])
            ->middleware('permission:feriado')->name('rrhh.feriados.create');
        Route::post('/feriados', [FeriadoController::class, 'store'])
            ->middleware('permission:feriado')->name('rrhh.feriados.store');
        Route::get('/feriados/{id}/edit', [FeriadoController::class, 'edit'])
            ->middleware('permission:feriado')->name('rrhh.feriados.edit');
        Route::put('/feriados/{id}', [FeriadoController::class, 'update'])
            ->middleware('permission:feriado')->name('rrhh.feriados.update');
        Route::delete('/feriados/{id}', [FeriadoController::class, 'destroy'])
            ->middleware('permission:feriado')->name('rrhh.feriados.destroy');
    });

    Route::prefix('reportes')->group(function () {
        Route::get('/costos', [ReportesController::class, 'costos'])->middleware('permission:resumen_costos')->name('reportes.costos.index');
        Route::get('/alertas', [AlertasController::class, 'index'])->middleware('permission:notificaciones')->name('reportes.alertas.index');
        Route::post('/alertas/marcar-todas', [AlertasController::class, 'marcarTodasLeidas'])->middleware('permission:notificaciones')->name('reportes.alertas.marcar-todas');
        Route::post('/alertas/{id}/marcar-leida', [AlertasController::class, 'marcarComoLeida'])->middleware('permission:notificaciones')->name('reportes.alertas.marcar-leida');
        Route::delete('/alertas/{id}', [AlertasController::class, 'eliminar'])->middleware('permission:notificaciones')->name('reportes.alertas.eliminar');
        Route::get('/log', [LogController::class, 'index'])->middleware('permission:log_cambios')->name('reportes.log.index');
    });

    Route::prefix('configuracion')->group(function () {
        Route::get('/usuarios', [\App\Http\Controllers\Configuracion\UsuarioController::class, 'index'])->middleware('permission:usuario')->name('configuracion.usuarios.index');
        Route::get('/usuarios/create', [\App\Http\Controllers\Configuracion\UsuarioController::class, 'create'])->middleware('permission:usuario')->name('configuracion.usuarios.create');
        Route::post('/usuarios', [\App\Http\Controllers\Configuracion\UsuarioController::class, 'store'])->middleware('permission:usuario')->name('configuracion.usuarios.store');
        Route::get('/usuarios/{id}/edit', [\App\Http\Controllers\Configuracion\UsuarioController::class, 'edit'])->middleware('permission:usuario')->name('configuracion.usuarios.edit');
        Route::put('/usuarios/{id}', [\App\Http\Controllers\Configuracion\UsuarioController::class, 'update'])->middleware('permission:usuario')->name('configuracion.usuarios.update');
        Route::delete('/usuarios/{id}', [\App\Http\Controllers\Configuracion\UsuarioController::class, 'destroy'])->middleware('permission:usuario')->name('configuracion.usuarios.destroy');
        Route::post('/usuarios/{id}/restaurar', [\App\Http\Controllers\Configuracion\UsuarioController::class, 'restaurar'])->middleware('permission:usuario')->name('configuracion.usuarios.restaurar');
    });
});

