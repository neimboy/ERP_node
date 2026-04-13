<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Proveedor;
use App\Models\DetalleCompra;
use Illuminate\Http\Request;

class ComprasController extends Controller
{
    public function index()
    {
        $compras = Compra::with('proveedor', 'detalles')->get();
        return view('compras.index', compact('compras'));
    }

    public function create()
    {
        $proveedores = Proveedor::all();
        return view('compras.create', compact('proveedores'));
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
