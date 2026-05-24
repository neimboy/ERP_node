<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\DetalleCotizacion;
use App\Models\Orden;
use App\Models\DetalleOrden;
use App\Models\DetalleOrdenCompra;
use App\Models\Oportunidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

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

        // Comprobar vencimiento en las cotizaciones listadas y marcarlas si corresponde
        $cotizaciones->getCollection()->each(function ($c) {
            if (method_exists($c, 'checkAndMarkVencida')) {
                $c->checkAndMarkVencida();
            }
        });

        $estados = ['BORRADOR','ENVIADA','ACEPTADA','RECHAZADA','CONVERTIDA','VENCIDA','PENDIENTE'];

        return view('ventas.cotizaciones.index', compact('cotizaciones', 'q', 'estado', 'estados'));
    }

    /**
     * Mostrar formulario para crear cotización
     */
    public function create()
    {
        $clientes = Cliente::orderBy('Nombre')->get();
        // Traer productos con stock (>0) desde el módulo Inventario
        $productos = Producto::withSum('inventarios', 'Cantidad')
            ->select('Id_Producto', 'Nombre', 'Precio_Venta', 'Precio_Compra')
            ->get()
            ->map(function ($p) {
                $p->stock = $p->inventarios_sum ?? 0;

                // Si no tiene Precio_Venta, intentar obtener el último costo de compra recibido
                if (empty($p->Precio_Venta) || floatval($p->Precio_Venta) == 0) {
                    $lastCosto = DetalleOrdenCompra::where('Id_Producto', $p->Id_Producto)
                        ->whereHas('ordenCompra', function ($q) {
                            $q->where('Estado', 'Recibida');
                        })
                        ->orderByDesc('Id_Detalle')
                        ->value('Costo');

                    if (!is_null($lastCosto) && $lastCosto !== '') {
                        $p->Precio_Venta = $lastCosto;
                    } elseif (isset($p->Precio_Compra)) {
                        $p->Precio_Venta = $p->Precio_Compra;
                    } else {
                        $p->Precio_Venta = 0;
                    }
                }

                return $p;
            })
            ->filter(function ($p) {
                return ($p->stock ?? 0) > 0;
            })
            ->values();

        // Si no hay productos con stock, fallback a todos los productos
        if ($productos->isEmpty()) {
            $productos = Producto::select('Id_Producto', 'Nombre', 'Precio_Venta', 'Precio_Compra')
                ->get()
                ->map(function ($p) {
                    // intentar fallback a último costo o precio de compra
                    if (empty($p->Precio_Venta) || floatval($p->Precio_Venta) == 0) {
                        $lastCosto = DetalleOrdenCompra::where('Id_Producto', $p->Id_Producto)
                            ->whereHas('ordenCompra', function ($q) {
                                $q->where('Estado', 'Recibida');
                            })
                            ->orderByDesc('Id_Detalle')
                            ->value('Costo');

                        if (!is_null($lastCosto) && $lastCosto !== '') {
                            $p->Precio_Venta = $lastCosto;
                        } elseif (isset($p->Precio_Compra)) {
                            $p->Precio_Venta = $p->Precio_Compra;
                        } else {
                            $p->Precio_Venta = 0;
                        }
                    }
                    $p->stock = 0;
                    return $p;
                });
        }

        $oportunidadId = request('oportunidad_id') ?? request('Id_Oportunidad') ?? null;
        $selectedCliente = request('Id_Cliente') ?? null;

        // Asegurarse de que $oportunidad exista (null por defecto) para evitar undefined variable en compact
        $oportunidad = null;
        $prefilledItems = null;
        if ($oportunidadId) {
            $oportunidad = Oportunidad::find($oportunidadId);
            if ($oportunidad) {
                // Si la oportunidad tiene monto estimado, prellenar una línea con ese monto
                if (!empty($oportunidad->Monto_Estimado)) {
                    $prefilledItems = [[
                        'Id_Producto' => null,
                        'Nombre' => 'Estimado - Oportunidad #' . $oportunidad->Id_Oportunidad,
                        'cantidad' => 1,
                        'precio' => floatval($oportunidad->Monto_Estimado),
                        'descuento' => 0,
                    ]];
                }

                // Si no se envió cliente, usar el de la oportunidad
                if (empty($selectedCliente)) {
                    $selectedCliente = $oportunidad->Id_Cliente ?? $selectedCliente;
                }
            }
        }

        return view('ventas.cotizaciones.create', compact('clientes', 'productos', 'oportunidadId', 'selectedCliente', 'prefilledItems', 'oportunidad'));
    }

    /**
     * Guardar cotización
     */
    public function store(Request $request)
    {
        try {
            // Validar datos mínimos (Fecha_Vencimiento es obligatoria según requerimiento)
            $request->validate([
                'Id_Cliente' => 'required',
                'Fecha_Vencimiento' => 'required|date'
            ]);

            Log::info('Creando cotización', ['cliente' => $request->Id_Cliente]);

            $cotizacion = DB::transaction(function () use ($request) {
                // Preparar datos de cotización respetando esquemas legacy/modernos
                $cotData = [];
                // Cliente
                if (Schema::hasColumn('cotizaciones', 'Id_Cliente')) {
                    $cotData['Id_Cliente'] = $request->Id_Cliente;
                } else {
                    $cotData['cliente_id'] = $request->Id_Cliente;
                }

                // Fechas: aceptar fecha enviada por el formulario si existe
                $inputFecha = $request->input('Fecha') ?? $request->input('fecha') ?? null;
                $inputFV = $request->input('Fecha_Vencimiento') ?? $request->input('fecha_vencimiento') ?? null;

                if (Schema::hasColumn('cotizaciones', 'Fecha')) {
                    $cotData['Fecha'] = $inputFecha ? $inputFecha : now();
                } else {
                    $cotData['fecha'] = $inputFecha ? $inputFecha : now();
                }

                if (Schema::hasColumn('cotizaciones', 'Fecha_Vencimiento')) {
                    $cotData['Fecha_Vencimiento'] = $inputFV ? $inputFV : now()->addDays(30);
                } else {
                    $cotData['fecha_vencimiento'] = $inputFV ? $inputFV : now()->addDays(30);
                }

                // Estado default (usar valores: BORRADOR, ENVIADA, ACEPTADA, RECHAZADA)
                if (Schema::hasColumn('cotizaciones', 'Estado')) {
                    $cotData['Estado'] = $request->Estado ?? 'BORRADOR';
                } else {
                    $cotData['estado'] = $request->Estado ?? 'BORRADOR';
                }

                // Total inicial
                if (Schema::hasColumn('cotizaciones', 'Total')) {
                    $cotData['Total'] = 0;
                } else {
                    $cotData['total'] = 0;
                }

                // Oportunidad (si se envia desde la vista de oportunidad)
                if ($request->oportunidad_id) {
                    if (Schema::hasColumn('cotizaciones', 'oportunidad_id')) {
                        $cotData['oportunidad_id'] = $request->oportunidad_id;
                    } elseif (Schema::hasColumn('cotizaciones', 'Id_Oportunidad')) {
                        $cotData['Id_Oportunidad'] = $request->oportunidad_id;
                    }
                }

                // Evitar claves duplicadas por mayúsculas/minúsculas (ej. Fecha vs fecha)
                $normalized = [];
                foreach ($cotData as $k => $v) {
                    $lk = strtolower($k);
                    if (!isset($normalized[$lk])) {
                        $normalized[$lk] = [$k, $v];
                    }
                }
                $finalCotData = [];
                foreach ($normalized as $entry) {
                    $finalCotData[$entry[0]] = $entry[1];
                }

                // Crear cotización usando datos normalizados
                $cotizacion = Cotizacion::create($finalCotData);

                Log::info('Cotización creada: ' . $cotizacion->Id_Cotizacion);

                $total = 0;

                // Crear detalles (soporte para 'lineas' legacy)
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

                // Si se usara una forma alternativa 'items', soportarla también
                if ($request->items) {
                    foreach ($request->items as $it) {
                        $prodId = $it['producto_id'] ?? $it['Id_Producto'] ?? null;
                        $producto = Producto::find($prodId);
                        if (!$producto && $prodId) {
                            // intentar buscar por Id_Producto
                            $producto = Producto::where('Id_Producto', $prodId)->first();
                        }

                        $cantidad = floatval($it['cantidad'] ?? 1);
                        $precio = floatval($it['precio'] ?? ($producto->Precio_Venta ?? 0));
                        $descuento = floatval($it['descuento'] ?? 0);

                        $subtotal = $cantidad * $precio;
                        $desc_monto = $subtotal * ($descuento / 100);
                        $subtotal_final = $subtotal - $desc_monto;

                        DetalleCotizacion::create([
                            'Id_Cotizacion' => $cotizacion->Id_Cotizacion,
                            'Id_Producto' => $producto->Id_Producto ?? null,
                            'Cantidad' => $cantidad,
                            'Precio_Unitario' => $precio,
                            'Descuento' => $descuento,
                            'Total' => $subtotal_final
                        ]);

                        $total += $subtotal_final;
                    }
                }

                // Calcular desglose financiero
                $costosDirectos = round(floatval($total), 2);
                $gastosGenerales = round($costosDirectos * 0.06, 2); // 6% sobre CD
                $utilidad = round($costosDirectos * 0.10, 2); // 10% sobre CD
                $subtotalCalc = round($costosDirectos + $gastosGenerales + $utilidad, 2);
                $impuestoCalc = round($subtotalCalc * 0.18, 2); // IGV 18%
                $presupuestoTotal = round($subtotalCalc + $impuestoCalc, 2);

                // Preparar datos para actualizar según columnas existentes
                $updateData = [];
                if (Schema::hasColumn('cotizaciones', 'Total')) {
                    $updateData['Total'] = $presupuestoTotal;
                }
                if (Schema::hasColumn('cotizaciones', 'total')) {
                    $updateData['total'] = $presupuestoTotal;
                }
                if (Schema::hasColumn('cotizaciones', 'Subtotal')) {
                    $updateData['Subtotal'] = $subtotalCalc;
                }
                if (Schema::hasColumn('cotizaciones', 'subtotal')) {
                    $updateData['subtotal'] = $subtotalCalc;
                }
                if (Schema::hasColumn('cotizaciones', 'Impuesto')) {
                    $updateData['Impuesto'] = $impuestoCalc;
                }
                if (Schema::hasColumn('cotizaciones', 'impuesto')) {
                    $updateData['impuesto'] = $impuestoCalc;
                }

                // Fallback: si no se detecta columna 'Total' intentar actualizar 'Total' (legacy)
                if (empty($updateData)) {
                    $updateData['Total'] = $presupuestoTotal;
                }

                // Normalizar claves del array para evitar duplicados case-insensitive
                $norm = [];
                foreach ($updateData as $k => $v) {
                    $lk = strtolower($k);
                    if (!isset($norm[$lk])) {
                        $norm[$lk] = [$k, $v];
                    }
                }
                $finalUpdate = [];
                foreach ($norm as $entry) {
                    $finalUpdate[$entry[0]] = $entry[1];
                }

                // Actualizar totales
                $cotizacion->update($finalUpdate);

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
        // Actualizar estado si está vencida antes de mostrar
        if (method_exists($cotizacion, 'checkAndMarkVencida')) {
            $cotizacion->checkAndMarkVencida();
        }

        $cotizacion->load(['cliente', 'detalles.producto']);
        return view('ventas.cotizaciones.show', compact('cotizacion'));
    }

    /**
     * Mostrar formulario para editar
     */
    public function edit(Cotizacion $cotizacion)
    {
        $clientes = Cliente::orderBy('Nombre')->get();
        // Misma lógica que en create: mostrar productos disponibles en inventario
        $productos = Producto::withSum('inventarios', 'Cantidad')
            ->select('Id_Producto', 'Nombre', 'Precio_Venta', 'Precio_Compra')
            ->get()
            ->map(function ($p) {
                $p->stock = $p->inventarios_sum ?? 0;

                if (empty($p->Precio_Venta) || floatval($p->Precio_Venta) == 0) {
                    $lastCosto = DetalleOrdenCompra::where('Id_Producto', $p->Id_Producto)
                        ->whereHas('ordenCompra', function ($q) {
                            $q->where('Estado', 'Recibida');
                        })
                        ->orderByDesc('Id_Detalle')
                        ->value('Costo');

                    if (!is_null($lastCosto) && $lastCosto !== '') {
                        $p->Precio_Venta = $lastCosto;
                    } elseif (isset($p->Precio_Compra)) {
                        $p->Precio_Venta = $p->Precio_Compra;
                    } else {
                        $p->Precio_Venta = 0;
                    }
                }

                return $p;
            })
            ->filter(function ($p) {
                return ($p->stock ?? 0) > 0;
            })
            ->values();

        if ($productos->isEmpty()) {
            $productos = Producto::select('Id_Producto', 'Nombre', 'Precio_Venta', 'Precio_Compra')
                ->get()
                ->map(function ($p) {
                    if (empty($p->Precio_Venta) || floatval($p->Precio_Venta) == 0) {
                        $lastCosto = DetalleOrdenCompra::where('Id_Producto', $p->Id_Producto)
                            ->whereHas('ordenCompra', function ($q) {
                                $q->where('Estado', 'Recibida');
                            })
                            ->orderByDesc('Id_Detalle')
                            ->value('Costo');

                        if (!is_null($lastCosto) && $lastCosto !== '') {
                            $p->Precio_Venta = $lastCosto;
                        } elseif (isset($p->Precio_Compra)) {
                            $p->Precio_Venta = $p->Precio_Compra;
                        } else {
                            $p->Precio_Venta = 0;
                        }
                    }
                    $p->stock = 0;
                    return $p;
                });
        }
        $cotizacion->load('detalles');

        return view('ventas.cotizaciones.edit', compact('cotizacion', 'clientes', 'productos'));
    }

    /**
     * Actualizar cotización
     */
    public function update(Request $request, Cotizacion $cotizacion)
    {
        try {
            // Actualizar datos básicos
            $updateBasic = [
                'Id_Cliente' => $request->Id_Cliente,
                'Estado' => $request->Estado,
            ];

            // Permitir actualizar Fecha_Vencimiento si se envía
            $inputFV = $request->input('Fecha_Vencimiento') ?? $request->input('fecha_vencimiento') ?? null;
            if ($inputFV) {
                if (Schema::hasColumn('cotizaciones', 'Fecha_Vencimiento')) {
                    $updateBasic['Fecha_Vencimiento'] = $inputFV;
                } elseif (Schema::hasColumn('cotizaciones', 'fecha_vencimiento')) {
                    $updateBasic['fecha_vencimiento'] = $inputFV;
                }
            }

            $cotizacion->update($updateBasic);

            // Si se envían líneas nuevas, reemplazarlas y recalcular totales
            if ($request->lineas || $request->items) {
                DB::transaction(function () use ($request, $cotizacion) {
                    // Eliminar detalles actuales
                    $cotizacion->detalles()->delete();

                    $total = 0;

                    if ($request->lineas) {
                        foreach ($request->lineas as $linea) {
                            $producto = Producto::find($linea['Id_Producto']);
                            if (!$producto) continue;
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

                    if ($request->items) {
                        foreach ($request->items as $it) {
                            $prodId = $it['producto_id'] ?? $it['Id_Producto'] ?? null;
                            $producto = Producto::find($prodId);
                            $cantidad = floatval($it['cantidad'] ?? 1);
                            $precio = floatval($it['precio'] ?? ($producto->Precio_Venta ?? 0));
                            $descuento = floatval($it['descuento'] ?? 0);
                            $subtotal = $cantidad * $precio;
                            $desc_monto = $subtotal * ($descuento / 100);
                            $subtotal_final = $subtotal - $desc_monto;

                            DetalleCotizacion::create([
                                'Id_Cotizacion' => $cotizacion->Id_Cotizacion,
                                'Id_Producto' => $producto->Id_Producto ?? null,
                                'Cantidad' => $cantidad,
                                'Precio_Unitario' => $precio,
                                'Descuento' => $descuento,
                                'Total' => $subtotal_final
                            ]);

                            $total += $subtotal_final;
                        }
                    }

                    // Recalcular desglose
                    $costosDirectos = round(floatval($total), 2);
                    $gastosGenerales = round($costosDirectos * 0.06, 2);
                    $utilidad = round($costosDirectos * 0.10, 2);
                    $subtotalCalc = round($costosDirectos + $gastosGenerales + $utilidad, 2);
                    $impuestoCalc = round($subtotalCalc * 0.18, 2);
                    $presupuestoTotal = round($subtotalCalc + $impuestoCalc, 2);

                    $updateData = [];
                    if (Schema::hasColumn('cotizaciones', 'Total')) {
                        $updateData['Total'] = $presupuestoTotal;
                    }
                    if (Schema::hasColumn('cotizaciones', 'total')) {
                        $updateData['total'] = $presupuestoTotal;
                    }
                    if (Schema::hasColumn('cotizaciones', 'Subtotal')) {
                        $updateData['Subtotal'] = $subtotalCalc;
                    }
                    if (Schema::hasColumn('cotizaciones', 'subtotal')) {
                        $updateData['subtotal'] = $subtotalCalc;
                    }
                    if (Schema::hasColumn('cotizaciones', 'Impuesto')) {
                        $updateData['Impuesto'] = $impuestoCalc;
                    }
                    if (Schema::hasColumn('cotizaciones', 'impuesto')) {
                        $updateData['impuesto'] = $impuestoCalc;
                    }
                    if (empty($updateData)) {
                        $updateData['Total'] = $presupuestoTotal;
                    }

                    // Normalizar claves para evitar duplicados por mayúsculas/minúsculas
                    $norm2 = [];
                    foreach ($updateData as $k2 => $v2) {
                        $lk2 = strtolower($k2);
                        if (!isset($norm2[$lk2])) {
                            $norm2[$lk2] = [$k2, $v2];
                        }
                    }
                    $finalUpdate2 = [];
                    foreach ($norm2 as $e2) {
                        $finalUpdate2[$e2[0]] = $e2[1];
                    }

                    $cotizacion->update($finalUpdate2);
                });
            }

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
        // Solo cotizaciones ACEPTADA pueden generar orden
        $estado = strtoupper($cotizacion->Estado ?? $cotizacion->estado ?? '');
        if ($estado !== 'ACEPTADA') {
            return redirect()->back()->with('error', '❌ Solo las cotizaciones en estado ACEPTADA pueden generar una orden');
        }

        // Usamos una variable para guardar el Objeto de la Orden, no solo el ID
        $ordenCreada = null;

        DB::transaction(function () use (&$ordenCreada, $cotizacion) {
            // Recargar la cotización con lock para evitar condiciones de carrera
            $cot = Cotizacion::where('Id_Cotizacion', $cotizacion->Id_Cotizacion)->lockForUpdate()->first();

            if (!$cot) {
                throw new \Exception('Cotización no encontrada');
            }

            $cot->load('detalles', 'cliente');

            // Verificar si ya existe una orden asociada para no duplicar
            if (Schema::hasColumn('ordenes', 'Id_Cotizacion')) {
                $ordenExistente = Orden::where('Id_Cotizacion', $cot->Id_Cotizacion)->first();
                if ($ordenExistente) {
                    $ordenCreada = $ordenExistente;
                    return;
                }
            }

            // Mapeo dinámico de montos por si la tabla Ordenes tiene el mismo esquema de totales
            $orderData = [
                'Id_Cliente' => $cot->Id_Cliente ?? $cot->cliente_id ?? null,
                'Fecha'      => now(),
                'Estado'     => 'PENDIENTE',
            ];

            // Pasamos los desgloses financieros calculados en la cotización a la orden
            if (Schema::hasColumn('ordenes', 'Total'))     $orderData['Total'] = $cot->Total ?? $cot->total ?? 0;
            if (Schema::hasColumn('ordenes', 'total'))     $orderData['total'] = $cot->Total ?? $cot->total ?? 0;
            if (Schema::hasColumn('ordenes', 'Subtotal'))  $orderData['Subtotal'] = $cot->Subtotal ?? $cot->subtotal ?? 0;
            if (Schema::hasColumn('ordenes', 'subtotal'))  $orderData['subtotal'] = $cot->Subtotal ?? $cot->subtotal ?? 0;
            if (Schema::hasColumn('ordenes', 'Impuesto'))  $orderData['Impuesto'] = $cot->Impuesto ?? $cot->impuesto ?? 0;
            if (Schema::hasColumn('ordenes', 'impuesto'))  $orderData['impuesto'] = $cot->Impuesto ?? $cot->impuesto ?? 0;

            if (Schema::hasColumn('ordenes', 'Id_Cotizacion')) {
                $orderData['Id_Cotizacion'] = $cot->Id_Cotizacion;
            }

            // Crear la nueva orden
            $orden = Orden::create($orderData);

            // Crear los detalles de la orden traspasando cantidades y precios finales
            foreach ($cot->detalles as $detalle) {
                DetalleOrden::create([
                    'Id_Orden'    => $orden->Id_Orden,
                    'Id_Producto' => $detalle->Id_Producto,
                    'Cantidad'    => $detalle->Cantidad,
                    'Precio'      => $detalle->Precio_Unitario, // O $detalle->Total si guardas el neto
                ]);
            }

            // Marcar la cotización como CONVERTIDA
            if (Schema::hasColumn($cot->getTable(), 'Estado')) {
                $cot->Estado = 'CONVERTIDA';
            } else {
                $cot->estado = 'CONVERTIDA';
            }

            if (Schema::hasColumn($cot->getTable(), 'Id_Orden')) {
                $cot->Id_Orden = $orden->Id_Orden;
            }

            $cot->save();
            $ordenCreada = $orden; // Guardamos el modelo completo para el redirect
        });

        if ($ordenCreada) {
            return redirect()
                ->route('ordenes.show', $ordenCreada) // 👈 Pasamos el objeto del modelo completo
                ->with('success', '✅ Orden #' . $ordenCreada->Id_Orden . ' creada exitosamente a partir de la cotización');
        }

        return redirect()->back()->with('error', '❌ No se pudo crear la orden');

    } catch (\Exception $e) {
        Log::error('Error al convertir cotización a orden: ' . $e->getMessage(), ['exception' => $e]);
        return redirect()->back()->with('error', '❌ Error: ' . $e->getMessage());
    }
}

    /**
     * Marcar cotización como ACEPTADA (acción rápida)
     */
    public function aceptar(Request $request, Cotizacion $cotizacion)
    {
        try {
            $update = [];
            if (Schema::hasColumn($cotizacion->getTable(), 'Estado')) {
                $update['Estado'] = 'ACEPTADA';
            } elseif (Schema::hasColumn($cotizacion->getTable(), 'estado')) {
                $update['estado'] = 'ACEPTADA';
            }

            if (!empty($update)) {
                $cotizacion->update($update);
            }

            // Si se solicitó generar orden al aceptar, delegar a convertirAOrden
            if ($request->boolean('generar_orden')) {
                return $this->convertirAOrden($cotizacion);
            }

            return redirect()->route('cotizaciones.show', $cotizacion)->with('success', '✅ Cotización marcada como ACEPTADA');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }
}
