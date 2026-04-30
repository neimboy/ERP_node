<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\Orden;
use App\Services\IntegracionContableService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFacturaRequest;
use App\DTOs\FacturaDTO;
use App\Services\VentasService;

class FacturaController extends Controller
{
    protected VentasService $ventasService;

    public function __construct(VentasService $ventasService)
    {
        $this->ventasService = $ventasService;
    }
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

        // Crear la factura en BD
        $factura = Factura::create($data);

        // Intentar registrar asiento contable si existe el servicio
        try {
            IntegracionContableService::registrarFactura($factura);
        } catch (\Exception $e) {
            // seguir el flujo aunque falle la integración contable
           // \Log::error('Integracion contable error: ' . $e->getMessage());
        }

        // Crear DTO desde el modelo y delegar a VentasService para emitir en background
        $dto = FacturaDTO::fromModel($factura);
        $this->ventasService->emitirFactura($dto);

        return redirect()->route('facturas.index')->with('success', 'La factura se está procesando y será enviada por correo');
    }
}
