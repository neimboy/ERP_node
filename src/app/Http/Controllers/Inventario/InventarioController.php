<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index()
    {
        $inventarios = Inventario::with('producto')->get();
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
}
