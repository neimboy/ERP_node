<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// CONTABILIDAD
use App\Http\Controllers\Contabilidad\AsientoController;
use App\Http\Controllers\Contabilidad\PlanContableController;
use App\Http\Controllers\Contabilidad\PeriodoController;
// VENTAS
use App\Http\Controllers\Ventas\ClienteController;
use App\Http\Controllers\Ventas\OrdenController;
use App\Http\Controllers\Ventas\FacturaController;
use App\Http\Controllers\Ventas\PagoController;
use App\Http\Controllers\Ventas\OportunidadController;

// RRHH
use App\Http\Controllers\RRHH\EmpleadoController;
use App\Http\Controllers\RRHH\NominaController;

// INVENTARIO
use App\Http\Controllers\Inventario\AlmacenController;
use App\Http\Controllers\Inventario\ProductoController;
use App\Http\Controllers\Inventario\InventarioController;
use App\Http\Controllers\Inventario\MovimientosController;
use App\Http\Controllers\Inventario\ComprasController;
use App\Http\Controllers\Inventario\ProveedoresController;
use App\Http\Controllers\Inventario\CategoriaController;

// PRODUCCIÓN
use App\Http\Controllers\Produccion\ProyectoController;
use App\Http\Controllers\Produccion\AsignacionController;

// ADMINISTRACIÓN
use App\Http\Controllers\Admin\UserController;

// ==========================================
// RAÍZ — Welcome page con opciones login/register
// ==========================================
Route::get('/', function () {
    return view('welcome');
});

// ==========================================
// DASHBOARD & PERFIL (AUTENTICADOS)
// ==========================================
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================
// ERP - MÓDULOS (CONTROL DE ACCESO)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // 🔐 ADMIN - GESTIÓN DE USUARIOS
    // Solo accesible por el Super Admin
    Route::prefix('admin')
        ->middleware('role:Super Admin')
        ->group(function () {
            Route::get('usuarios', [UserController::class, 'index'])->name('admin.users');
            Route::post('usuarios/{id}/rol', [UserController::class, 'assignRole'])->name('admin.users.assignRole');
        });

   // 🔵 CONTABILIDAD
    Route::prefix('contabilidad')
    ->middleware(['role:Super Admin,Contador'])
    ->group(function () {

        // Plan de Cuentas
        Route::get('plan-cuentas', [PlanContableController::class, 'index'])->name('contabilidad.plan_cuentas');
        Route::post('plan-cuentas', [PlanContableController::class, 'store'])->name('contabilidad.plan_cuentas.store');
        Route::get('plan-cuentas/{id}/edit', [PlanContableController::class, 'edit'])->name('contabilidad.plan_cuentas.edit');
        Route::put('plan-cuentas/{id}', [PlanContableController::class, 'update'])->name('contabilidad.plan_cuentas.update');
        Route::delete('plan-cuentas/{id}', [PlanContableController::class, 'destroy'])->name('contabilidad.plan_cuentas.destroy');

        // Asientos (CRUD)
        Route::resource('asientos', AsientoController::class)->parameters([
            'asientos' => 'asiento',
        ]);

        // Períodos contables
        Route::get('periodos', [PeriodoController::class, 'index'])->name('contabilidad.periodos');
        Route::post('periodos', [PeriodoController::class, 'store'])->name('contabilidad.periodos.store');
        Route::patch('periodos/{periodo}/toggle', [PeriodoController::class, 'toggleEstado'])->name('contabilidad.periodos.toggle');
        Route::delete('periodos/{periodo}', [PeriodoController::class, 'destroy'])->name('contabilidad.periodos.destroy');

        // Reportes
        Route::get('libro-mayor', [AsientoController::class, 'libroMayor'])->name('contabilidad.libro_mayor');
        Route::get('estado-resultados', [AsientoController::class, 'estadoResultados'])->name('contabilidad.estado_resultados');
        Route::get('balance-general', [AsientoController::class, 'balanceGeneral'])->name('contabilidad.balance_general');
        Route::get('igv-mensual', [AsientoController::class, 'igvMensual'])->name('contabilidad.igv_mensual');
        Route::get('estado-resultados-semestral', [AsientoController::class, 'estadoResultadosSemestral'])->name('contabilidad.estado_resultados_semestral');
        Route::get('resumen-gerencial', [AsientoController::class, 'resumenGerencial'])->name('contabilidad.resumen_gerencial');
    });

    // 🟢 VENTAS
    Route::prefix('ventas')
        ->middleware('role:Super Admin,Ventas')
        ->group(function () {
            Route::resource('clientes', ClienteController::class);
            Route::resource('ordenes', OrdenController::class);
            Route::resource('facturas', FacturaController::class);
            Route::resource('pagos', PagoController::class);
            // Forzar nombre del parámetro a `oportunidad` (singular en español)
            Route::resource('oportunidades', OportunidadController::class)
                ->parameters(['oportunidades' => 'oportunidad']);
            // Ruta personalizada para cerrar una oportunidad
            Route::post('oportunidades/{oportunidad}/cerrar', [OportunidadController::class, 'cerrar'])
                ->name('oportunidades.cerrar');
            // Marcar oportunidad como ganada (genera orden+factura a través del servicio)
            Route::post('oportunidades/{oportunidad}/ganar', [OportunidadController::class, 'ganarOportunidad'])
                ->name('oportunidades.ganar');
            // Ruta para generar una Orden de Venta desde una oportunidad ganada
            Route::post('oportunidades/{oportunidad}/generar-orden', [OportunidadController::class, 'generarOrden'])
                ->name('oportunidades.generarOrden');
        });

    // 🟡 INVENTARIO
    Route::prefix('inventario')
        ->middleware('role:Super Admin,Almacenero')
        ->group(function () {
            // Dashboard general del módulo Inventarios
            Route::get('/', [InventarioController::class, 'dashboard'])
                ->name('inventario.dashboard');

            // CRUD de almacenes 
            Route::resource('almacenes', AlmacenController::class)
                ->parameters(['almacenes' => 'almacen']);
            // CRUD de productos
            Route::resource('productos', ProductoController::class);

            // CRUD de categorías ✅ nuevo
            Route::resource('categorias', CategoriaController::class);

            // Control general de inventario
            Route::resource('inventarios', InventarioController::class);

            // Movimientos de stock (entradas/salidas)
            Route::resource('movimientos', MovimientosController::class);

            // Compras
            Route::resource('compras', ComprasController::class);

            Route::patch('compras/{id}/estado', [ComprasController::class, 'updateEstado'])
                ->name('compras.updateEstado');
            // Proveedores
            Route::resource('proveedores', ProveedoresController::class)
                ->parameters(['proveedores' => 'proveedor']);
            // Consultar stock
            Route::get('/inventario/stock/{producto}/{almacen}', 
                [InventarioController::class, 'verStock']);
            // Verificar stock antes de vender
            Route::get('/inventario/verificar-stock/{producto}/{almacen}/{cantidad}', 
            [InventarioController::class, 'verificarStock'])->name('inventario.verificarStock');

            Route::get('/compras/{id}/comprobante/pdf', [ComprasController::class, 'comprobantePdf'])
                 ->name('compras.comprobante.pdf');
            Route::get('inventario/compras/{id}/comprobante/preview', [ComprasController::class, 'comprobantePreview'])
                 ->name('compras.comprobante.preview');

            Route::delete('notificaciones/{notificacion}', [ProductoController::class, 'destroyNotificacion'])->name('notificaciones.destroy');

        });


    // 🔴 RRHH
    Route::prefix('rrhh')
        ->middleware('role:Super Admin,RRHH') 
        ->group(function () {
            Route::resource('empleados', EmpleadoController::class);
            Route::resource('nominas', NominaController::class);
        });

    // PRODUCCIÓN
    Route::prefix('produccion')
        ->middleware('role:Super Admin,Produccion')
        ->group(function () {
            Route::get('proyectos/tipo', [ProyectoController::class, 'tipoProyecto'])->name('proyectos.tipo');
            Route::get('proyectos/create-produccion', [ProyectoController::class, 'createProduccion'])->name('proyectos.create-produccion');
            Route::get('proyectos/create-servicio', [ProyectoController::class, 'createServicio'])->name('proyectos.create-servicio');
            Route::post('proyectos/store-produccion', [ProyectoController::class, 'storeProduccion'])->name('proyectos.store-produccion');
            Route::post('proyectos/store-servicio', [ProyectoController::class, 'storeServicio'])->name('proyectos.store-servicio');
            Route::get('proyectos/productos-disponibles', [ProyectoController::class, 'productosDisponibles'])->name('proyectos.productos');
            Route::post('proyectos/{proyecto}/agregar-productos', [ProyectoController::class, 'agregarProductos'])->name('proyectos.agregar-productos');
            Route::post('proyectos/{proyecto}/devolver-productos', [ProyectoController::class, 'devolverProductos'])->name('proyectos.devolver-productos');
            Route::post('proyectos/{proyecto}/notificar-sin-stock', [ProyectoController::class, 'notificarSinStock'])->name('proyectos.notificar-stock');
            Route::post('notificar-sin-stock', [ProyectoController::class, 'notificarSinStockGeneral'])->name('proyectos.notificar-stock-general');
            Route::put('proyectos/{proyecto}/update-produccion', [ProyectoController::class, 'updateProduccion'])->name('proyectos.update-produccion');
            Route::put('proyectos/{proyecto}/update-servicio', [ProyectoController::class, 'updateServicio'])->name('proyectos.update-servicio');
            Route::get('proyectos/{proyecto}/reporte', [ProyectoController::class, 'reporte'])->name('proyectos.reporte');
            Route::resource('proyectos', ProyectoController::class)->except(['create', 'store', 'update']);
            Route::resource('asignaciones', AsignacionController::class);
        });

});
// ==========================
// AUTH (BREEZE)
// ==========================
require __DIR__.'/auth.php';

