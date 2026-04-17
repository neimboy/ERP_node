<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\Orden;
use App\Services\IntegracionContableService;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    // ... otros métodos
    public function index()
    {
        $facturas = Factura::with('orden.cliente')->orderByDesc('Fecha')->paginate(15);
        return view('ventas.facturas.index', compact('facturas'));
    }

    public function show(Factura $factura)
    {
        // Eager load order, client and order details with products
        $factura->load('orden.cliente', 'orden.detalles.producto');

        return view('ventas.facturas.show', compact('factura'));
    }

    public function store(Request $request)
    {
        // Tu lógica existente para crear la factura...
        $factura = Factura::create($request->all());

        // 🆕 Generar asiento contable automáticamente
        try {
            IntegracionContableService::registrarFactura($factura);
        } catch (\Exception $e) {
            return back()->with('error', 'Factura creada, pero error contable: ' . $e->getMessage());
        }

        return redirect()->route('facturas.index')->with('success', 'Factura registrada con asiento contable');
    }
}
