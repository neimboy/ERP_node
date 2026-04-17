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

class OrdenController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Super Admin,Ventas']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ordenes = Orden::with(['cliente', 'detalles.producto'])->orderByDesc('Fecha')->paginate(15);
        return view('ventas.ordenes.index', compact('ordenes'));
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
        $orden = null;

        DB::transaction(function () use ($request, &$orden) {
            $orden = Orden::create([
                'Id_Cliente' => $request->Id_Cliente,
                'Fecha' => now(),
                'Estado' => 'Pendiente',
            ]);

            $total = 0;

            foreach ($request->lineas as $line) {
                $producto = Producto::where('Id_Producto', $line['Id_Producto'])->lockForUpdate()->first();

                if (!$producto) {
                    throw ValidationException::withMessages(['lineas' => "Producto no encontrado ({$line['Id_Producto']})"]);
                }

                $cantidad = (int) $line['cantidad'];

                if ($producto->stock < $cantidad) {
                    throw ValidationException::withMessages(['stock' => "Stock insuficiente para {$producto->Nombre}"]);
                }

                // Reducir stock (método en el modelo Producto)
                $producto->decrementStock($cantidad);

                $precio = $producto->Precio_Venta ?? 0;

                DetalleOrden::create([
                    'Id_Orden' => $orden->Id_Orden,
                    'Id_Producto' => $producto->Id_Producto,
                    'Cantidad' => $cantidad,
                    'Precio' => $precio,
                ]);

                $total += $precio * $cantidad;
            }

            $factura = Factura::create([
                'Id_Orden' => $orden->Id_Orden,
                'Fecha' => now(),
                'Total' => $total,
                'Estado_Pago' => 'Pendiente',
            ]);

            // Integración contable (si está disponible)
            try {
                if (class_exists('\App\Services\IntegracionContableService')) {
                    \App\Services\IntegracionContableService::registrarFactura($factura);
                } else {
                    Log::warning('IntegracionContableService no disponible - TODO: integrar');
                }
            } catch (\Exception $e) {
                Log::error('Error Integracion contable: ' . $e->getMessage());
            }
        });

        return redirect()->route('ordenes.show', $orden?->Id_Orden ?? 0)->with('success', 'Orden creada correctamente.');
    }
}

