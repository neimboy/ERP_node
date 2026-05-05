<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrdenRequest;
use App\Models\Cliente;
use App\Models\Orden;
use App\Models\Producto;
use App\Models\DetalleOrden;
use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Services\VentasService;

class OrdenController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $q = request('q');
        $estado = request('estado');

        $ordenes = Orden::with(['cliente', 'detalles.producto'])
            ->when($q, function ($query) use ($q) {
                $query->where('Id_Orden', $q)
                      ->orWhereHas('cliente', function ($q2) use ($q) { $q2->where('Nombre', 'like', "%{$q}%"); });
            })
            ->when($estado, function ($query) use ($estado) {
                $query->where('Estado', $estado);
            })
            ->orderByDesc('Fecha')
            ->paginate(10)
            ->withQueryString();

        return view('ventas.ordenes.index', compact('ordenes', 'q', 'estado'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('Nombre')->get();
        $productos = Producto::select('Id_Producto', 'Nombre', 'Precio_Venta')->get();

        return view('ventas.ordenes.create', compact('clientes', 'productos'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Orden $orden)
    {
        $orden->load('detalles.producto', 'cliente');
        return view('ventas.ordenes.show', compact('orden'));
    }

    public function edit(Orden $orden)
    {
        $clientes = Cliente::orderBy('Nombre')->get();
        $productos = Producto::select('Id_Producto', 'Nombre', 'Precio_Venta')->get();
        $orden->load('detalles');
        return view('ventas.ordenes.edit', compact('orden', 'clientes', 'productos'));
    }

    public function update(Request $request, Orden $orden)
    {
        $data = $request->validate([
            'Estado' => 'required|string|max:50',
        ]);

        $orden->update(['Estado' => $data['Estado']]);

        return redirect()->route('ordenes.show', $orden)->with('success', 'Orden actualizada.');
    }

    public function destroy(Orden $orden)
    {
        if ($orden->factura) {
            return back()->with('error', 'No se puede eliminar una orden que ya tiene factura.');
        }

        // eliminar detalles primero
        $orden->detalles()->delete();
        $orden->delete();

        return redirect()->route('ordenes.index')->with('success', 'Orden eliminada.');
    }

    /**
     * Genera una factura para la orden si no existe y redirige a la vista de factura.
     */
    public function facturar(Orden $orden)
    {
        if ($orden->factura) {
            return redirect()->route('facturas.show', $orden->factura);
        }

        $factura = null;

        DB::transaction(function () use ($orden, &$factura) {
            $orden->load('detalles.producto');

            $total = $orden->detalles->reduce(function ($carry, $d) {
                return $carry + (($d->Precio ?? 0) * ($d->Cantidad ?? 0));
            }, 0);

            $factura = Factura::create([
                'Id_Orden' => $orden->Id_Orden,
                'Fecha' => now(),
                'Total' => $total,
                'Estado_Pago' => 'Pendiente',
            ]);

            try {
                if (class_exists('\App\Services\IntegracionContableService')) {
                    \App\Services\IntegracionContableService::registrarFactura($factura);
                } else {
                    Log::warning('IntegracionContableService no disponible al facturar orden ' . $orden->Id_Orden);
                }
            } catch (\Exception $e) {
                Log::error('Error integracion contable al facturar: ' . $e->getMessage());
            }

            // Opcional: actualizar estado de la orden
            // $orden->update(['Estado' => 'Facturada']);
        });

        return redirect()->route('facturas.show', $factura)->with('success', 'Factura generada correctamente.');
    }

    public function store(StoreOrdenRequest $request)
    {
        // Delegar la lógica compleja al servicio de ventas (transacciones, stock y facturación)
        $ventasService = new VentasService();
        $orden = $ventasService->crearOrdenConLineas((int) $request->Id_Cliente, $request->lineas);

        return redirect()->route('ordenes.show', $orden?->Id_Orden ?? 0)->with('success', 'Orden creada correctamente.');
    }
}

