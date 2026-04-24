<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// CONTABILIDAD
use App\Http\Controllers\Contabilidad\AsientoController;
use App\Http\Controllers\Contabilidad\PlanContableController;

// VENTAS
use App\Http\Controllers\Ventas\ClienteController;
use App\Http\Controllers\Ventas\OrdenController;
use App\Http\Controllers\Ventas\FacturaController;
use App\Http\Controllers\Ventas\PagoController;

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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

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
        ->middleware('role:Super Admin,Contador')
        ->group(function () {

            // Plan de Cuentas
            Route::get('plan-cuentas', [PlanContableController::class, 'index'])->name('contabilidad.plan_cuentas');
            Route::post('plan-cuentas', [PlanContableController::class, 'store'])->name('contabilidad.plan_cuentas.store');

            // Asientos (CRUD) - Aquí podrías añadir ->middleware('permission:view_contabilidad') si usas permisos individuales
            Route::resource('asientos', AsientoController::class);

            // Reportes
            Route::get('libro-mayor', [AsientoController::class, 'libroMayor'])->name('contabilidad.libro_mayor');
            Route::get('estado-resultados', [AsientoController::class, 'estadoResultados'])->name('contabilidad.estado_resultados');
            Route::get('balance-general', [AsientoController::class, 'balanceGeneral'])->name('contabilidad.balance_general');
        });

    // 🟢 VENTAS
    Route::prefix('ventas')
        ->middleware('role:Super Admin,Ventas')
        ->group(function () {
            Route::resource('clientes', ClienteController::class);
            Route::resource('ordenes', OrdenController::class);
            Route::resource('facturas', FacturaController::class);
            Route::resource('pagos', PagoController::class);
        });

    // 🟡 INVENTARIO
    Route::prefix('inventario')
        ->middleware('role:Super Admin,Almacenero')
        ->group(function () {
            // Dashboard general del módulo Inventarios
            Route::get('/', [InventarioController::class, 'dashboard'])
                ->name('inventario.dashboard');

            // CRUD de almacenes
            Route::resource('almacenes', AlmacenController::class);

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

            // Proveedores
            Route::resource('proveedores', ProveedoresController::class);
        });




    // 🔴 RRHH
    Route::prefix('rrhh')
        ->middleware('role:Super Admin,RRHH')
        ->group(function () {
            Route::resource('empleados', EmpleadoController::class);
            Route::resource('nominas', NominaController::class);
        });

    // 🟣 PRODUCCIÓN
    Route::prefix('produccion')
        ->middleware('role:Super Admin,Produccion')
        ->group(function () {
            Route::resource('proyectos', ProyectoController::class);
            Route::resource('asignaciones', AsignacionController::class);
        });
});

// ==========================
// AUTH (BREEZE)
// ==========================
require __DIR__.'/auth.php';
