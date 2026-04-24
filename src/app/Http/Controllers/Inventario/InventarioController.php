<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Almacen;
use App\Models\Movimiento;
use App\Models\Compra;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index()
    {
        $inventarios = Inventario::with('producto')->get();
        // ✅ apunta a resources/views/inventarios/index.blade.php
        return view('inventarios.index', compact('inventarios'));
    }

    public function updateStock(Request $request, Inventario $inventario)
    {
        $request->validate([
            'cantidad_actual' => 'required|integer',
        ]);

        $inventario->update($request->all());
        return redirect()->route('inventarios.index')->with('success', 'Stock actualizado correctamente.');
    }

    public function dashboard()
    {
        return view('inventarios.dashboard', [
            'productosCount' => Producto::count(),
            'almacenesCount' => Almacen::count(),
            'stockTotal'     => Inventario::sum('cantidad'),
            'movimientos'    => Movimiento::latest()->take(5)->get(),
            'compras'        => Compra::latest()->take(5)->get(),
        ]);
    }
}
