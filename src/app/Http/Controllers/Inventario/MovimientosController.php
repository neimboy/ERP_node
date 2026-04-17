<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Movimiento;
use App\Models\Producto;
use Illuminate\Http\Request;

class MovimientosController extends Controller
{
    public function index()
    {
        $movimientos = Movimiento::with('producto')->get();
        return view('movimientos.index', compact('movimientos'));
    }

    public function create()
    {
        $productos = Producto::all();
        return view('movimientos.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'tipo' => 'required|in:entrada,salida',
            'cantidad' => 'required|integer',
            'fecha' => 'required|date',
            'referencia' => 'nullable|string',
        ]);

        Movimiento::create($request->all());
        return redirect()->route('movimientos.index')->with('success', 'Movimiento registrado correctamente.');
    }
}
