<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Almacen;
use App\Models\Compra;
use App\Models\Orden; // ventas
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index()
    {
        $inventarios = Inventario::with(['producto', 'almacen'])->get();
        return view('inventarios.inventario.index', compact('inventarios'));
    }

    public function create()
    {
        $productos = Producto::all();
        $almacenes = Almacen::all();
        return view('inventarios.inventario.create', compact('productos', 'almacenes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Id_Producto'  => 'required|exists:productos,Id_Producto',
            'Id_Almacen'   => 'required|exists:almacenes,Id_Almacen',
            'Cantidad'     => 'required|integer|min:0',
            'Stock_Minimo' => 'required|integer|min:0',
        ]);

        Inventario::create($request->all());
        return redirect()->route('inventarios.index')->with('success', 'Inventario registrado correctamente.');
    }

    public function show(Inventario $inventario)
    {
        return view('inventarios.inventario.show', compact('inventario'));
    }

    public function edit(Inventario $inventario)
    {
        $productos = Producto::all();
        $almacenes = Almacen::all();
        return view('inventarios.inventario.edit', compact('inventario','productos','almacenes'));
    }

    public function update(Request $request, Inventario $inventario)
    {
        $request->validate([
            'Cantidad'     => 'required|integer|min:0',
            'Stock_Minimo' => 'required|integer|min:0',
        ]);

        $inventario->update($request->only(['Cantidad','Stock_Minimo']));
        return redirect()->route('inventarios.index')->with('success', 'Inventario actualizado correctamente.');
    }

    public function destroy(Inventario $inventario)
    {
        $inventario->delete();
        return redirect()->route('inventarios.index')->with('success', 'Inventario eliminado correctamente.');
    }

    public function updateStock(Request $request, Inventario $inventario)
    {
        $request->validate([
            'Cantidad' => 'required|integer|min:0',
        ]);

        $inventario->update($request->only('Cantidad'));
        return redirect()->route('inventarios.index')->with('success', 'Stock actualizado correctamente.');
    }

    public function dashboard()
    {
        return view('inventarios.inventario.dashboard', [
            'productosCount' => Producto::count(),
            'almacenesCount' => Almacen::count(),
            'stockTotal'     => Inventario::sum('Cantidad'),
            'compras'        => Compra::latest()->take(5)->get(),
            'ordenes'        => Orden::latest()->take(5)->get(),
        ]);
    }
}
