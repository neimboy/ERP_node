<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\Orden;
use App\Services\IntegracionContableService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFacturaRequest;

class FacturaController extends Controller
{
    // ... otros métodos
    public function index()
    {
        $q = request('q');
        $facturas = Factura::with('orden.cliente')
            ->when($q, function ($query) use ($q) {
                $query->where('Id_Factura', $q)
                      ->orWhereHas('orden.cliente', function ($q2) use ($q) { $q2->where('Nombre', 'like', "%{$q}%"); });
            })
            ->orderByDesc('Fecha')
            ->paginate(10)
            ->withQueryString();

        return view('ventas.facturas.index', compact('facturas', 'q'));
    }

    public function show(Factura $factura)
    {
        // Eager load order, client and order details with products
        $factura->load('orden.cliente', 'orden.detalles.producto');

        return view('ventas.facturas.show', compact('factura'));
    }

    public function store(StoreFacturaRequest $request)
    {
        $data = $request->validated();

        $factura = Factura::create($data);

        try {
            IntegracionContableService::registrarFactura($factura);
        } catch (\Exception $e) {
            return back()->with('error', 'Factura creada, pero error contable: ' . $e->getMessage());
        }

        return redirect()->route('facturas.index')->with('success', 'Factura registrada con asiento contable');
    }
}
