<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\Proveedor;
use App\Models\DetalleCompra;
use Illuminate\Http\Request;

class ComprasController extends Controller
{
    public function index()
    {
        $compras = Compra::with('proveedor', 'detalles')->get();
        // ✅ apunta a resources/views/inventarios/index.blade.php
        return view('inventarios.index', compact('compras'));
    }

    public function create()
    {
        $proveedores = Proveedor::all();
        // ✅ apunta a resources/views/inventarios/create.blade.php
        return view('inventarios.create', compact('proveedores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'fecha_compra' => 'required|date',
            'total' => 'required|numeric',
        ]);

        $compra = Compra::create($request->all());

        foreach ($request->detalles as $detalle) {
            DetalleCompra::create([
                'compra_id' => $compra->id,
                'producto_id' => $detalle['producto_id'],
                'cantidad' => $detalle['cantidad'],
                'precio_unitario' => $detalle['precio_unitario'],
            ]);
        }

        return redirect()->route('compras.index')->with('success', 'Compra registrada correctamente.');
    }
}
