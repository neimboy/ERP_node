<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\DetalleCotizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CotizacionController extends Controller
{
    /**
     * Listar todas las cotizaciones
     */
    public function index()
    {
        $q = request('q');
        $estado = request('estado');

        $cotizaciones = Cotizacion::with(['cliente', 'detalles.producto'])
            ->when($q, function ($query) use ($q) {
                $query->where('Id_Cotizacion', 'like', "%{$q}%")
                      ->orWhereHas('cliente', function ($q2) use ($q) {
                          $q2->where('Nombre', 'like', "%{$q}%");
                      });
            })
            ->when($estado, function ($query) use ($estado) {
                $query->where('Estado', $estado);
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $estados = ['Pendiente', 'Aceptada', 'Rechazada', 'Convertida', 'Vencida'];

        return view('ventas.cotizaciones.index', compact('cotizaciones', 'q', 'estado', 'estados'));
    }

    /**
     * Mostrar formulario para crear cotización
     */
    public function create()
    {
        $clientes = Cliente::orderBy('Nombre')->get();
        $productos = Producto::select('Id_Producto', 'Nombre', 'Precio_Venta')->get();

        return view('ventas.cotizaciones.create', compact('clientes', 'productos'));
    }

    /**
     * Guardar cotización
     */
    public function store(Request $request)
    {
        try {
            Log::info('Creando cotización', ['cliente' => $request->Id_Cliente]);

            $cotizacion = DB::transaction(function () use ($request) {
                // Crear cotización
                $cotizacion = Cotizacion::create([
                    'Id_Cliente' => $request->Id_Cliente,
                    'Fecha' => now(),
                    'Fecha_Vencimiento' => now()->addDays(30),
                    'Estado' => 'Pendiente',
                    'Total' => 0
                ]);

                Log::info('Cotización creada: ' . $cotizacion->Id_Cotizacion);

                $total = 0;

                // Crear detalles
                if ($request->lineas) {
                    foreach ($request->lineas as $linea) {
                        $producto = Producto::find($linea['Id_Producto']);
                        
                        if (!$producto) {
                            throw new \Exception('Producto no encontrado: ' . $linea['Id_Producto']);
                        }

                        $cantidad = intval($linea['cantidad'] ?? 1);
                        $precio = floatval($linea['precio'] ?? $producto->Precio_Venta ?? 0);
                        $descuento = floatval($linea['descuento'] ?? 0);
                        
                        $subtotal = $cantidad * $precio;
                        $desc_monto = $subtotal * ($descuento / 100);
                        $subtotal_final = $subtotal - $desc_monto;

                        DetalleCotizacion::create([
                            'Id_Cotizacion' => $cotizacion->Id_Cotizacion,
                            'Id_Producto' => $producto->Id_Producto,
                            'Cantidad' => $cantidad,
                            'Precio_Unitario' => $precio,
                            'Descuento' => $descuento,
                            'Total' => $subtotal_final
                        ]);

                        $total += $subtotal_final;
                    }
                }

                // Actualizar total
                $cotizacion->update(['Total' => $total]);

                return $cotizacion;
            });

            Log::info('Cotización creada exitosamente: ' . $cotizacion->Id_Cotizacion);

            return redirect()
                ->route('cotizaciones.show', $cotizacion)
                ->with('success', '✅ Cotización #' . $cotizacion->Id_Cotizacion . ' creada exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al crear cotización: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalle de cotización
     */
    public function show(Cotizacion $cotizacion)
    {
        $cotizacion->load(['cliente', 'detalles.producto']);
        return view('ventas.cotizaciones.show', compact('cotizacion'));
    }

    /**
     * Mostrar formulario para editar
     */
    public function edit(Cotizacion $cotizacion)
    {
        $clientes = Cliente::orderBy('Nombre')->get();
        $productos = Producto::select('Id_Producto', 'Nombre', 'Precio_Venta')->get();
        $cotizacion->load('detalles');
        
        return view('ventas.cotizaciones.edit', compact('cotizacion', 'clientes', 'productos'));
    }

    /**
     * Actualizar cotización
     */
    public function update(Request $request, Cotizacion $cotizacion)
    {
        try {
            $cotizacion->update([
                'Id_Cliente' => $request->Id_Cliente,
                'Estado' => $request->Estado,
            ]);

            return redirect()
                ->route('cotizaciones.show', $cotizacion)
                ->with('success', 'Cotización actualizada correctamente');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar cotización
     */
    public function destroy(Cotizacion $cotizacion)
    {
        try {
            DB::transaction(function () use ($cotizacion) {
                $cotizacion->detalles()->delete();
                $cotizacion->delete();
            });

            Log::info('Cotización eliminada: ' . $cotizacion->Id_Cotizacion);

            return redirect()
                ->route('cotizaciones.index')
                ->with('success', '✅ Cotización eliminada correctamente');

        } catch (\Exception $e) {
            Log::error('Error al eliminar cotización: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', '❌ Error al eliminar: ' . $e->getMessage());
        }
    }

    /**
     * Convertir cotización a orden
     */
    public function convertirAOrden(Cotizacion $cotizacion)
    {
        try {
            if ($cotizacion->Estado !== 'Pendiente') {
                return redirect()->back()->with('error', 'Solo se pueden convertir cotizaciones Pendientes');
            }

            $cotizacion->update(['Estado' => 'Convertida']);

            return redirect()
                ->route('cotizaciones.show', $cotizacion)
                ->with('success', 'Cotización convertida a orden');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
