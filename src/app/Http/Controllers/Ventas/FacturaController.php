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