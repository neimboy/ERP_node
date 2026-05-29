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

        $ordenes = Orden::with(['cliente', 'detalles.producto', 'cotizacion'])
            ->when($q, function ($query) use ($q) {
                $query->where('Id_Orden', $q)
                      ->orWhereHas('cliente', function ($q2) use ($q) {
                          $q2->where('Nombre', 'like', "%{$q}%");
                      });
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
    public function show($id)
    {
        // Forzamos la búsqueda manual por tu clave primaria legacy
        $orden = Orden::where('Id_Orden', $id)->firstOrFail();

        $orden->load('detalles.producto', 'cliente', 'cotizacion');
        return view('ventas.ordenes.show', compact('orden'));
    }

    public function edit($id)
    {
        $orden = Orden::where('Id_Orden', $id)->firstOrFail();

        $clientes = Cliente::orderBy('Nombre')->get();
        $productos = Producto::select('Id_Producto', 'Nombre', 'Precio_Venta')->get();
        $orden->load('detalles');

        return view('ventas.ordenes.edit', compact('orden', 'clientes', 'productos'));
    }

    public function update(Request $request, $id)
    {
        $orden = Orden::where('Id_Orden', $id)->firstOrFail();

        $data = $request->validate([
            'Estado' => 'required|string|max:50',
        ]);

        $orden->update(['Estado' => $data['Estado']]);

        // Redirección explícita usando el ID limpio
        return redirect()->route('ordenes.show', $orden->Id_Orden)->with('success', 'Orden actualizada.');
    }

    public function destroy($id)
    {
        $orden = Orden::where('Id_Orden', $id)->firstOrFail();

        if ($orden->factura) {
            return back()->with('error', 'No se puede eliminar una orden que ya tiene factura.');
        }

        // Eliminar detalles primero
        $orden->detalles()->delete();
        $orden->delete();

        return redirect()->route('ordenes.index')->with('success', 'Orden eliminada.');
    }

    /**
     * Genera una factura para la orden si no existe y redirige a la vista de factura.
     */
    public function facturar($id)
    {
        $orden = Orden::where('Id_Orden', $id)->firstOrFail();

        if ($orden->factura) {
            return redirect()->route('facturas.show', $orden->factura->Id_Factura ?? $orden->factura->id);
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
        });

        return redirect()->route('facturas.show', $factura->Id_Factura ?? $factura->id)->with('success', 'Factura generada correctamente.');
    }

    /**
     * Confirmar ejecución de la orden: marcar EJECUTADA y disparar evento.
     */
    public function confirmarEjecucion($id)
    {
        $orden = Orden::where('Id_Orden', $id)->with('detalles')->firstOrFail();

        $current = strtoupper($orden->Estado ?? $orden->estado ?? '');
        if ($current === 'EJECUTADA') {
            return redirect()->route('ordenes.show', $orden->Id_Orden)->with('info', 'Orden ya marcada como ejecutada.');
        }

        DB::transaction(function () use ($orden) {
            $orden->Estado = 'EJECUTADA';
            $orden->save();

            try {
                event(new \App\Events\OrdenEjecutada($orden, $orden->detalles));
            } catch (\Exception $e) {
                Log::error('Error dispatching OrdenEjecutada event: ' . $e->getMessage());
            }
        });

        return redirect()->route('ordenes.show', $orden->Id_Orden)->with('success', 'Orden marcada como EJECUTADA y evento ORDEN_EJECUTADA disparado.');
    }

    public function store(StoreOrdenRequest $request)
    {
        $ventasService = new VentasService();
        $orden = $ventasService->crearOrdenConLineas((int) $request->Id_Cliente, $request->lineas);

        return redirect()->route('ordenes.show', $orden?->Id_Orden ?? $orden?->getKey() ?? 0)->with('success', 'Orden creada correctamente.');
    }
}

