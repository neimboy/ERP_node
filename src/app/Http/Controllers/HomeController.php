<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asiento;
use App\Models\Factura;
use App\Models\Producto;
use App\Models\Empleado;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        $stats = [];

        // ==========================
        // CONTABILIDAD
        // ==========================
        if ($user->can('view_contabilidad')) {
            $stats['total_asientos'] = Asiento::count();
            $stats['total_debe'] = DB::table('asiento_detalle')->sum('Debe') ?? 0;
            $stats['total_haber'] = DB::table('asiento_detalle')->sum('Haber') ?? 0;
        }

        // ==========================
        // VENTAS
        // ==========================
        if ($user->can('view_ventas')) {
            $stats['total_facturas'] = Factura::count();
            $stats['total_ventas'] = Factura::sum('Total') ?? 0;
            $stats['total_pagos'] = DB::table('pagos')->sum('Monto') ?? 0;
        }

        // ==========================
        // INVENTARIO
        // ==========================
        if ($user->can('view_inventario')) {
            $stats['total_productos'] = Producto::count();

            $stats['stock_bajo'] = DB::table('inventario')
                ->whereColumn('Cantidad', '<', 'Stock_Minimo')
                ->count();
        }

        // ==========================
        // RRHH
        // ==========================
        if ($user->can('view_rrhh')) {
            $stats['total_empleados'] = Empleado::count();
        }

        return view('dashboard', compact('stats', 'user'));
    }
}