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
            // Validar datos mínimos
            $request->validate([
                'Id_Cliente' => 'required',
                'Fecha_Vencimiento' => 'required|date'
            ]);

            Log::info('Creando cotización', ['cliente' => $request->Id_Cliente]);

            $cotizacion = DB::transaction(function () use ($request) {
                // Preparar datos básicos de la cotización
                $cotData = [];
                if (Schema::hasColumn('cotizaciones', 'Id_Cliente')) {
                    $cotData['Id_Cliente'] = $request->Id_Cliente;
                } else {
                    $cotData['cliente_id'] = $request->Id_Cliente;
                }

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

                if (Schema::hasColumn('cotizaciones', 'Estado')) {
                    $cotData['Estado'] = $request->Estado ?? 'BORRADOR';
                } else {
                    $cotData['estado'] = $request->Estado ?? 'BORRADOR';
                }

                // Crear cotización
                $cotizacion = Cotizacion::create($cotData);

                $totalLineasCents = 0; // suma de totales netos de las líneas (en centavos)
                $cdCents = 0; // Costos directos en centavos

                // Normalizar suministro de líneas (lineas || items)
                $inputLines = $request->lineas ?? $request->items ?? [];

                if (empty($inputLines)) {
                    throw new \Exception('La cotización debe contener al menos una línea');
                }

                foreach ($inputLines as $line) {
                    $prodId = $line['Id_Producto'] ?? $line['producto_id'] ?? null;
                    $producto = null;
                    if ($prodId) {
                        $producto = Producto::find($prodId);
                    }

                    // Determinar cantidades y precios
                    $cantidad = intval($line['cantidad'] ?? $line['Cantidad'] ?? 1);
                    $precioUnitario = isset($line['precio']) ? floatval($line['precio']) : ($producto->Precio_Venta ?? 0);

                    // Determinar costo unitario: preferir Precio_Compra, fallback a último costo de compra
                    $costoUnitario = 0;
                    if ($producto) {
                        if (isset($producto->Precio_Compra) && floatval($producto->Precio_Compra) > 0) {
                            $costoUnitario = floatval($producto->Precio_Compra);
                        } else {
                            $lastCosto = DetalleOrdenCompra::where('Id_Producto', $producto->Id_Producto)
                                ->whereHas('ordenCompra', function ($q) {
                                    $q->where('Estado', 'Recibida');
                                })
                                ->orderByDesc('Id_Detalle')
                                ->value('Costo');
                            if (!is_null($lastCosto) && $lastCosto !== '') {
                                $costoUnitario = floatval($lastCosto);
                            }
                        }
                    } else {
                        // Producto no encontrado: permitir línea con precio/costo explícito si fue enviado
                        $costoUnitario = isset($line['costo']) ? floatval($line['costo']) : 0;
                    }

                    $descuento = floatval($line['descuento'] ?? $line['Descuento'] ?? 0);

                    // Trabajar en centavos para evitar redondeos prematuros
                    $precioCents = (int) round(floatval($precioUnitario) * 100);
                    $costoCents = (int) round(floatval($costoUnitario) * 100);

                    $subtotalLineaCents = $precioCents * $cantidad;
                    $descuentoLineaCents = (int) round($subtotalLineaCents * ($descuento / 100.0));
                    $totalLineaCents = $subtotalLineaCents - $descuentoLineaCents;

                    DetalleCotizacion::create([
                        'Id_Cotizacion' => $cotizacion->Id_Cotizacion,
                        'Id_Producto' => $producto->Id_Producto ?? null,
                        'Cantidad' => $cantidad,
                        'Precio_Unitario' => $precioCents / 100.0,
                        'Costo_Unitario' => $costoCents / 100.0,
                        'Descuento' => $descuento,
                        'Total' => $totalLineaCents / 100.0
                    ]);

                    $totalLineasCents += $totalLineaCents;
                    $cdCents += ($costoCents * $cantidad);
                }

                // Cálculos financieros (usar centavos y redondear sólo al persistir)
                $gastosGeneralesCents = (int) round($cdCents * 0.06);
                $utilidadCents = (int) round($cdCents * 0.10);
                $subtotalCalcCents = $cdCents + $gastosGeneralesCents + $utilidadCents;
                $impuestoCents = (int) round($subtotalCalcCents * 0.18);
                $presupuestoTotalCents = $subtotalCalcCents + $impuestoCents;

                $updateData = [];
                if (Schema::hasColumn('cotizaciones', 'Costos_Directos')) {
                    $updateData['Costos_Directos'] = $cdCents / 100.0;
                }
                if (Schema::hasColumn('cotizaciones', 'Gastos_Generales')) {
                    $updateData['Gastos_Generales'] = $gastosGeneralesCents / 100.0;
                }
                if (Schema::hasColumn('cotizaciones', 'Utilidad')) {
                    $updateData['Utilidad'] = $utilidadCents / 100.0;
                }
                if (Schema::hasColumn('cotizaciones', 'Subtotal')) {
                    $updateData['Subtotal'] = $subtotalCalcCents / 100.0;
                }
                if (Schema::hasColumn('cotizaciones', 'Impuesto')) {
                    $updateData['Impuesto'] = $impuestoCents / 100.0;
                }
                if (Schema::hasColumn('cotizaciones', 'Total')) {
                    $updateData['Total'] = $presupuestoTotalCents / 100.0;
                }
                if (empty($updateData)) {
                    $updateData['Total'] = $presupuestoTotalCents / 100.0;
                }

                $cotizacion->update($updateData);

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

            // Crear los detalles de la orden traspasando cantidades, precios y snapshot de costos
            foreach ($cot->detalles as $detalle) {
                DetalleOrden::create([
                    'Id_Orden'        => $orden->Id_Orden,
                    'Id_Producto'     => $detalle->Id_Producto,
                    'Cantidad'        => $detalle->Cantidad,
                    'Precio_Unitario' => $detalle->Precio_Unitario ?? $detalle->Precio ?? 0,
                    'Costo_Unitario'  => $detalle->Costo_Unitario ?? 0,
                    'Descuento'       => $detalle->Descuento ?? 0,
                    'Total'           => $detalle->Total ?? (($detalle->Precio_Unitario ?? $detalle->Precio ?? 0) * $detalle->Cantidad),
                    // Mantener el campo legacy `Precio` para compatibilidad
                    'Precio'          => $detalle->Precio_Unitario ?? $detalle->Precio ?? 0,
                ]);
            }

            // Vincular la cotización con la orden sin cambiar su estado (debe permanecer ACEPTADA)
            if (Schema::hasColumn($cot->getTable(), 'Id_Orden')) {
                $cot->Id_Orden = $orden->Id_Orden;
            }

            // Asegurar que permanezca en ACEPTADA
            $cot->{$colEstado} = 'ACEPTADA';
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
            // Operación transaccional: revalidar stock, crear orden y disparar evento
            $ordenCreada = null;

            DB::transaction(function () use (&$ordenCreada, $cotizacion, $request) {
                // Bloquear la cotización para evitar race conditions
                $cot = Cotizacion::where('Id_Cotizacion', $cotizacion->Id_Cotizacion)
                    ->lockForUpdate()
                    ->with('detalles')
                    ->firstOrFail();

                // 1) Revalidar stock en tiempo real
                $insuficientes = [];
                foreach ($cot->detalles as $detalle) {
                    if (empty($detalle->Id_Producto)) continue; // saltar líneas no relacionadas a catálogo

                    $producto = Producto::where('Id_Producto', $detalle->Id_Producto)->first();
                    if (!$producto) {
                        $insuficientes[] = [
                            'producto' => $detalle->Id_Producto,
                            'error' => 'Producto no encontrado'
                        ];
                        continue;
                    }

                    $stockDisponible = $producto->stock ?? 0;
                    if ($stockDisponible < $detalle->Cantidad) {
                        $insuficientes[] = [
                            'producto' => $producto->Id_Producto,
                            'requerido' => $detalle->Cantidad,
                            'disponible' => $stockDisponible
                        ];
                    }
                }

                if (!empty($insuficientes)) {
                    throw new \App\Exceptions\StockInsufficientException('Stock insuficiente', $insuficientes);
                }

                // 2) (Opcional) Verificar crédito del cliente si existe el servicio
                if (class_exists('\App\Services\ClienteService')) {
                    try {
                        $clienteService = new \App\Services\ClienteService();
                        if (method_exists($clienteService, 'verificarCreditoDisponible')) {
                            $creditoOk = $clienteService->verificarCreditoDisponible($cot->Id_Cliente ?? $cot->cliente_id ?? null, $cot->Total ?? $cot->total ?? 0);
                            if (!$creditoOk) {
                                throw new \Exception('Cliente sin crédito suficiente');
                            }
                        }
                    } catch (\Exception $e) {
                        // Si la verificación de crédito falla por ausencia de datos, tratamos como error de validación
                        throw $e;
                    }
                }

                // 3) Marcar ACEPTADA
                $colEstado = Schema::hasColumn($cot->getTable(), 'Estado') ? 'Estado' : 'estado';
                $cot->{$colEstado} = 'ACEPTADA';
                $cot->save();

                // 4) Crear Orden de Venta (mapeo simple)
                $orderData = [
                    'Id_Cliente' => $cot->Id_Cliente ?? $cot->cliente_id ?? null,
                    'Fecha' => now(),
                    'Estado' => 'PENDIENTE',
                ];

                if (Schema::hasColumn('ordenes', 'Total'))     $orderData['Total'] = $cot->Total ?? $cot->total ?? 0;
                if (Schema::hasColumn('ordenes', 'Subtotal'))  $orderData['Subtotal'] = $cot->Subtotal ?? $cot->subtotal ?? 0;
                if (Schema::hasColumn('ordenes', 'Impuesto'))  $orderData['Impuesto'] = $cot->Impuesto ?? $cot->impuesto ?? 0;
                if (Schema::hasColumn('ordenes', 'Id_Cotizacion')) $orderData['Id_Cotizacion'] = $cot->Id_Cotizacion;

                $orden = Orden::create($orderData);

                foreach ($cot->detalles as $detalle) {
                    DetalleOrden::create([
                        'Id_Orden'        => $orden->Id_Orden,
                        'Id_Producto'     => $detalle->Id_Producto,
                        'Cantidad'        => $detalle->Cantidad,
                        'Precio_Unitario' => $detalle->Precio_Unitario ?? $detalle->Precio ?? 0,
                        'Costo_Unitario'  => $detalle->Costo_Unitario ?? 0,
                        'Descuento'       => $detalle->Descuento ?? 0,
                        'Total'           => $detalle->Total ?? (($detalle->Precio_Unitario ?? $detalle->Precio ?? 0) * $detalle->Cantidad),
                        'Precio'          => $detalle->Precio_Unitario ?? $detalle->Precio ?? 0,
                    ]);
                }

                // 5) Vincular cotización con la orden y mantener el estado ACEPTADA
                if (Schema::hasColumn($cot->getTable(), 'Id_Orden')) {
                    $cot->Id_Orden = $orden->Id_Orden;
                }
                $cot->{$colEstado} = 'ACEPTADA';
                $cot->save();

                $ordenCreada = $orden;

                // 6) Disparar evento interno ORDEN_APROBADA
                try {
                    event(new \App\Events\OrdenAprobada($ordenCreada, $cot));
                } catch (\Exception $e) {
                    // Loguear pero no revertir la transacción sólo por fallo en listeners
                    Log::error('Error dispatching OrdenAprobada event: ' . $e->getMessage());
                }
            });

            if ($ordenCreada) {
                return redirect()->route('ordenes.show', $ordenCreada)
                    ->with('success', '✅ Orden #' . $ordenCreada->Id_Orden . ' creada y evento ORDEN_APROBADA disparado');
            }

            return redirect()->route('cotizaciones.show', $cotizacion)->with('success', '✅ Cotización marcada como ACEPTADA');

        } catch (\App\Exceptions\StockInsufficientException $ex) {
            $details = $ex->getDetails();
            $msg = 'Stock insuficiente para uno o más productos';
            return redirect()->back()->with('error', $msg)->with('stock_errors', $details);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }
}
