<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\Orden;
use App\Models\DetalleFactura;
use App\Services\IntegracionContableService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFacturaRequest;
use App\DTOs\FacturaDTO;
use App\Services\VentasService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\FacturaEmitida;

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

    public function create()
    {
        $ordenes = Orden::with('cliente')
            ->where('Estado', 'EJECUTADA')
            ->whereDoesntHave('factura')
            ->orderByDesc('Fecha')
            ->get();

        return view('ventas.facturas.create', compact('ordenes'));
    }

    public function show(Factura $factura)
    {
        // Eager load factura detalles and related producto + orden cliente
        $factura->load('detalles.producto', 'orden.cliente');

        return view('ventas.facturas.show', compact('factura'));
    }
    public function store(StoreFacturaRequest $request)
    {
        $data = $request->validated();

        $orden = Orden::where('Id_Orden', $data['Id_Orden'])->with('detalles', 'cliente')->firstOrFail();

        DB::beginTransaction();
        try {
            // Crear factura mínimo y luego actualizar montos calculados
            $factura = Factura::create([
                'Id_Orden' => $orden->Id_Orden,
                'Id_Cliente' => $orden->Id_Cliente ?? null,
                'Fecha' => now()->toDateString(),
                'Subtotal' => 0,
                'IGV' => 0,
                'Total' => 0,
                'Estado_Pago' => 'PENDIENTE_PAGO',
                'Estado' => 'PENDIENTE_PAGO',
            ]);

            // Copiamos explícitamente los detalles desde la orden (snapshot)
            $subtotal = 0;
            foreach ($orden->detalles as $d) {
                $precioUnitario = $d->Precio_Unitario ?? $d->Precio ?? 0;
                $costoUnitario = $d->Costo_Unitario ?? 0;
                $descuento = $d->Descuento ?? 0;
                $cantidad = $d->Cantidad ?? 0;
                $totalLinea = $d->Total ?? ($precioUnitario * $cantidad - $descuento);

                DetalleFactura::create([
                    'Id_Factura' => $factura->Id_Factura,
                    'Id_Producto' => $d->Id_Producto,
                    'Cantidad' => $cantidad,
                    'Precio_Unitario' => $precioUnitario,
                    'Costo_Unitario' => $costoUnitario,
                    'Descuento' => $descuento,
                    'Total' => $totalLinea,
                    'Subtotal' => $totalLinea,
                ]);

                $subtotal += (float) $totalLinea;
            }

            // Cálculos: IGV 18%
            $igv = round($subtotal * 0.18, 2);
            $total = round($subtotal + $igv, 2);

            // Validación: que la suma de detalles coincida con el subtotal calculado
            $sumaDetalles = DetalleFactura::where('Id_Factura', $factura->Id_Factura)->sum('Total');
            if (round((float)$sumaDetalles, 2) !== round((float)$subtotal, 2)) {
                throw new \RuntimeException('La suma de los detalles no coincide con el subtotal calculado.');
            }

            // Actualizamos la factura con los montos finales
            $factura->Subtotal = $subtotal;
            $factura->IGV = $igv;
            $factura->Total = $total;
            $factura->Estado_Pago = 'PENDIENTE_PAGO';
            $factura->save();

            // Marcar la orden como FACTURADA
            $orden->Estado = 'FACTURADA';
            $orden->save();

            // Disparar evento para otros módulos
            try {
                event(new FacturaEmitida($factura, $orden));
            } catch (\Exception $e) {
                Log::error('Error al disparar evento FacturaEmitida: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()->route('facturas.show', $factura)->with('success', 'Factura generada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generando factura: ' . $e->getMessage());
            return back()->with('error', 'Error al generar la factura: ' . $e->getMessage())->withInput();
        }
    }
}
