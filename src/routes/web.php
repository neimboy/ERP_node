<?php

use Illuminate\Support\Facades\Route;

// Importación de Controladores
use App\Http\Controllers\Inventario\{AlmacenController, ProductoController};
use App\Http\Controllers\Ventas\{ClienteController, OrdenController, FacturaController};
use App\Http\Controllers\Contabilidad\{AsientoController};
use App\Http\Controllers\RRHH\{EmpleadoController, NominaController};
use App\Http\Controllers\Produccion\{ProyectoController};
use App\Http\Controllers\Contabilidad\PlanContableController;
Route::get('/', function () {
    return view('welcome');
});

// --- MÓDULO DE INVENTARIOS ---
Route::prefix('inventario')->group(function () {
    Route::resource('almacenes', AlmacenController::class);
    Route::resource('productos', ProductoController::class);
});

// --- MÓDULO DE VENTAS Y CRM ---
Route::prefix('ventas')->group(function () {
    Route::resource('clientes', ClienteController::class);
    Route::resource('ordenes', OrdenController::class);
    Route::resource('facturas', FacturaController::class);
});

// --- MÓDULO DE CONTABILIDAD ---
Route::prefix('contabilidad')->group(function () {
    Route::get('plan-cuentas', [PlanContableController::class, 'index'])->name('contabilidad.plan_cuentas');
    Route::post('plan-cuentas', [PlanContableController::class, 'store'])->name('contabilidad.plan_cuentas.store');
    Route::resource('asientos', AsientoController::class);
});

// --- MÓDULO DE RRHH ---
Route::prefix('rrhh')->group(function () {
    Route::resource('empleados', EmpleadoController::class);
    Route::resource('nominas', NominaController::class);
});

// --- MÓDULO DE PRODUCCIÓN ---
Route::prefix('produccion')->group(function () {
    Route::resource('proyectos', ProyectoController::class);
});