<?php

namespace App\Http\Controllers\Contabilidad;

use App\Http\Controllers\Controller;
use App\Models\Contabilidad\Asiento;
use App\Models\Contabilidad\AsientoDetalle;
use App\Models\Contabilidad\Periodo;
use App\Models\Contabilidad\CuentaContable;
use App\Models\Factura;
use App\Models\Inventario;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsientoController extends Controller
{
    // =========================================================
    // CRUD BÁSICO
    // =========================================================

    public function index()
    {
        $asientos = Asiento::with(['periodo', 'detalles.cuenta'])
            ->orderBy('Fecha', 'desc')
            ->paginate(20);

        return view('contabilidad.asientos.index', compact('asientos'));
    }

    public function create()
    {
        $periodos = Periodo::abiertos()->orderBy('Año', 'desc')->orderBy('Mes', 'desc')->get();
        $cuentas  = CuentaContable::orderBy('Codigo')->get();

        if ($periodos->isEmpty()) {
            return redirect()->route('asientos.index')
                ->with('warning', 'No hay períodos contables abiertos. Crea un período primero.');
        }

        return view('contabilidad.asientos.create', compact('periodos', 'cuentas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Id_Periodo'        => 'required|exists:periodos,Id_Periodo',
            'Fecha'             => 'required|date',
            'Glosa'             => 'required|string|max:255',
            'items'             => 'required|array|min:2',
            'items.*.Id_Cuenta' => 'required|exists:cuenta_contable,Id_Cuenta',
            'items.*.Debe'      => 'required|numeric|min:0',
            'items.*.Haber'     => 'required|numeric|min:0',
        ]);

        $items = collect($request->items)->filter(
            fn($i) => ($i['Debe'] > 0 || $i['Haber'] > 0)
        );

        if ($items->count() < 2) {
            return back()->withInput()
                ->withErrors(['items' => 'El asiento debe tener al menos 2 líneas con valores.']);
        }

        $totalDebe  = $items->sum('Debe');
        $totalHaber = $items->sum('Haber');

        if (abs($totalDebe - $totalHaber) > 0.01) {
            return back()->withInput()->withErrors([
                'partida_doble' => "El asiento no cuadra. Debe: S/. {$totalDebe} ≠ Haber: S/. {$totalHaber}.",
            ]);
        }

        DB::transaction(function () use ($request, $items) {
            $asiento = Asiento::create([
                'Id_Periodo' => $request->Id_Periodo,
                'Fecha'      => $request->Fecha,
                'Glosa'      => $request->Glosa,
            ]);

            foreach ($items as $item) {
                AsientoDetalle::create([
                    'Id_Asiento' => $asiento->Id_Asiento,
                    'Id_Cuenta'  => $item['Id_Cuenta'],
                    'Debe'       => $item['Debe'],
                    'Haber'      => $item['Haber'],
                ]);
            }
        });

        return redirect()->route('asientos.index')
            ->with('success', 'Asiento contable registrado correctamente.');
    }

    public function show($id)
    {
        $asiento = Asiento::with(['periodo', 'detalles.cuenta'])->findOrFail($id);
        $totalDebe  = $asiento->detalles->sum('Debe');
        $totalHaber = $asiento->detalles->sum('Haber');

        return view('contabilidad.asientos.show', compact('asiento', 'totalDebe', 'totalHaber'));
    }

    public function destroy($id)
    {
        $asiento = Asiento::findOrFail($id);
        $asiento->delete(); // cascade elimina detalles

        return redirect()->route('asientos.index')
            ->with('success', 'Asiento contable eliminado.');
    }

    // =========================================================
    // REPORTES FINANCIEROS
    // =========================================================

    /**
     * Libro Mayor – saldos acumulados por cuenta contable (PCGE)
     */
    public function libroMayor(Request $request)
    {
        $periodoId = $request->input('Id_Periodo');

        $query = CuentaContable::with(['detalles' => function ($q) use ($periodoId) {
            if ($periodoId) {
                $q->whereHas('asiento', fn($a) => $a->where('Id_Periodo', $periodoId));
            }
        }, 'detalles.asiento']);

        $cuentasMayor = $query->get()->map(function ($cuenta) {
            $totalDebe  = $cuenta->detalles->sum('Debe');
            $totalHaber = $cuenta->detalles->sum('Haber');

            $saldoDeudor   = 0;
            $saldoAcreedor = 0;

            if (in_array($cuenta->Tipo, ['Activo', 'Gasto', 'Costo', 'Activo (Contra)'])) {
                $diff = $totalDebe - $totalHaber;
                $diff >= 0 ? $saldoDeudor = $diff : $saldoAcreedor = abs($diff);
            } else { // Pasivo, Patrimonio, Ingreso
                $diff = $totalHaber - $totalDebe;
                $diff >= 0 ? $saldoAcreedor = $diff : $saldoDeudor = abs($diff);
            }

            return [
                'codigo'         => $cuenta->Codigo,
                'nombre'         => $cuenta->Nombre_Cuenta,
                'tipo'           => $cuenta->Tipo,
                'total_debe'     => $totalDebe,
                'total_haber'    => $totalHaber,
                'saldo_deudor'   => $saldoDeudor,
                'saldo_acreedor' => $saldoAcreedor,
            ];
        })->sortBy('codigo')->values();

        $periodos = Periodo::orderBy('Año', 'desc')->orderBy('Mes', 'desc')->get();

        return view('contabilidad.reportes.libro_mayor', compact('cuentasMayor', 'periodos', 'periodoId'));
    }

    /**
     * Balance General – Activo = Pasivo + Patrimonio
     */
    public function balanceGeneral()
    {
        $cuentas = CuentaContable::with('detalles')->get();

        $activos    = [];
        $pasivos    = [];
        $patrimonio = [];

        foreach ($cuentas as $cuenta) {
            $debe  = $cuenta->detalles->sum('Debe');
            $haber = $cuenta->detalles->sum('Haber');

            if (in_array($cuenta->Tipo, ['Activo', 'Activo (Contra)'])) {
                $saldo = $debe - $haber;
                if ($saldo != 0) {
                    $activos[] = ['codigo' => $cuenta->Codigo, 'nombre' => $cuenta->Nombre_Cuenta, 'monto' => $saldo];
                }
            } elseif ($cuenta->Tipo === 'Pasivo') {
                $saldo = $haber - $debe;
                if ($saldo != 0) {
                    $pasivos[] = ['codigo' => $cuenta->Codigo, 'nombre' => $cuenta->Nombre_Cuenta, 'monto' => $saldo];
                }
            } elseif ($cuenta->Tipo === 'Patrimonio') {
                $saldo = $haber - $debe;
                if ($saldo != 0) {
                    $patrimonio[] = ['codigo' => $cuenta->Codigo, 'nombre' => $cuenta->Nombre_Cuenta, 'monto' => $saldo];
                }
            }
        }

        usort($activos,    fn($a, $b) => strcmp($a['codigo'], $b['codigo']));
        usort($pasivos,    fn($a, $b) => strcmp($a['codigo'], $b['codigo']));
        usort($patrimonio, fn($a, $b) => strcmp($a['codigo'], $b['codigo']));

        $totalActivo          = collect($activos)->sum('monto');
        $totalPasivo          = collect($pasivos)->sum('monto');
        $totalPatrimonio      = collect($patrimonio)->sum('monto');
        $totalPasivoYPatrimonio = $totalPasivo + $totalPatrimonio;

        return view('contabilidad.reportes.balance_general', compact(
            'activos', 'pasivos', 'patrimonio',
            'totalActivo', 'totalPasivo', 'totalPatrimonio', 'totalPasivoYPatrimonio'
        ));
    }

    /**
     * Estado de Resultados simple (acumulado)
     */
    public function estadoResultados()
    {
        $cuentas = CuentaContable::with('detalles')->get();

        $detalleIngresos = [];
        $detalleGastos   = [];

        foreach ($cuentas as $cuenta) {
            $debe  = $cuenta->detalles->sum('Debe');
            $haber = $cuenta->detalles->sum('Haber');

            if ($cuenta->Tipo === 'Ingreso') {
                $monto = $haber - $debe;
                if ($monto != 0) {
                    $detalleIngresos[] = ['codigo' => $cuenta->Codigo, 'nombre' => $cuenta->Nombre_Cuenta, 'monto' => $monto];
                }
            } elseif (in_array($cuenta->Tipo, ['Gasto', 'Costo'])) {
                $monto = $debe - $haber;
                if ($monto != 0) {
                    $detalleGastos[] = ['codigo' => $cuenta->Codigo, 'nombre' => $cuenta->Nombre_Cuenta, 'monto' => $monto];
                }
            }
        }

        $ingresos    = collect($detalleIngresos)->sum('monto');
        $gastos      = collect($detalleGastos)->sum('monto');
        $utilidadNeta = $ingresos - $gastos;

        return view('contabilidad.reportes.estado_resultados', compact(
            'detalleIngresos', 'detalleGastos', 'ingresos', 'gastos', 'utilidadNeta'
        ));
    }

    /**
     * Estado de Resultados Semestral Comparativo
     * Agrupa los resultados mes a mes para ver la evolución del período.
     */
    public function estadoResultadosSemestral()
    {
        // Traemos todos los períodos con sus asientos y detalles
        $periodos = Periodo::with(['asientos.detalles.cuenta'])
            ->orderBy('Año')
            ->orderBy('Mes')
            ->get();

        $meses = [];

        foreach ($periodos as $periodo) {
            $ingresos = 0;
            $costos   = 0;
            $gastos   = 0;

            foreach ($periodo->asientos as $asiento) {
                foreach ($asiento->detalles as $detalle) {
                    $tipo = $detalle->cuenta->Tipo ?? '';

                    if ($tipo === 'Ingreso') {
                        $ingresos += ($detalle->Haber - $detalle->Debe);
                    } elseif ($tipo === 'Costo') {
                        $costos += ($detalle->Debe - $detalle->Haber);
                    } elseif ($tipo === 'Gasto') {
                        $gastos += ($detalle->Debe - $detalle->Haber);
                    }
                }
            }

            $utilidadBruta    = $ingresos - $costos;
            $utilidadOperativa = $utilidadBruta - $gastos;

            $meses[] = [
                'label'             => $periodo->label,
                'ingresos'          => $ingresos,
                'costos'            => $costos,
                'gastos'            => $gastos,
                'utilidad_bruta'    => $utilidadBruta,
                'utilidad_operativa' => $utilidadOperativa,
                'margen_bruto'      => $ingresos > 0 ? round($utilidadBruta / $ingresos * 100, 1) : 0,
                'margen_operativo'  => $ingresos > 0 ? round($utilidadOperativa / $ingresos * 100, 1) : 0,
            ];
        }

        // Totales acumulados del período
        $totalIngresos          = collect($meses)->sum('ingresos');
        $totalCostos            = collect($meses)->sum('costos');
        $totalGastos            = collect($meses)->sum('gastos');
        $totalUtilidadBruta     = collect($meses)->sum('utilidad_bruta');
        $totalUtilidadOperativa = collect($meses)->sum('utilidad_operativa');
        $margenBrutoTotal       = $totalIngresos > 0 ? round($totalUtilidadBruta / $totalIngresos * 100, 1) : 0;
        $margenOperativoTotal   = $totalIngresos > 0 ? round($totalUtilidadOperativa / $totalIngresos * 100, 1) : 0;

        return view('contabilidad.reportes.estado_resultados_semestral', compact(
            'meses',
            'totalIngresos', 'totalCostos', 'totalGastos',
            'totalUtilidadBruta', 'totalUtilidadOperativa',
            'margenBrutoTotal', 'margenOperativoTotal'
        ));
    }

    /**
     * IGV Mensual – Débito fiscal vs Crédito fiscal (SUNAT PDT 621)
     */
    public function igvMensual()
    {
        // Agrupamos IGV por período usando la cuenta 40 (Tributos por Pagar)
        $periodos = Periodo::with(['asientos.detalles' => function ($q) {
            $q->whereHas('cuenta', fn($c) => $c->where('Codigo', 'like', '40%'));
        }, 'asientos.detalles.cuenta'])
            ->orderBy('Año')->orderBy('Mes')
            ->get();

        $filasMensuales = [];

        foreach ($periodos as $periodo) {
            $igvVentas  = 0;
            $igvCompras = 0;

            foreach ($periodo->asientos as $asiento) {
                foreach ($asiento->detalles as $detalle) {
                    // IGV Ventas → cuentas 40 con movimiento Haber (débito fiscal)
                    $igvVentas  += $detalle->Haber;
                    // IGV Compras → cuentas 40 con movimiento Debe (crédito fiscal)
                    $igvCompras += $detalle->Debe;
                }
            }

            if ($igvVentas > 0 || $igvCompras > 0) {
                $filasMensuales[] = [
                    'label'      => $periodo->label,
                    'igv_ventas' => $igvVentas,
                    'igv_compras'=> $igvCompras,
                    'igv_neto'   => $igvVentas - $igvCompras,
                ];
            }
        }

        // También calculamos el total global directo
        $detallesIGV = AsientoDetalle::whereHas('cuenta', fn($q) => $q->where('Codigo', 'like', '40%'))->get();
        $igvVentasTotal  = $detallesIGV->sum('Haber');
        $igvComprasTotal = $detallesIGV->sum('Debe');
        $igvAPagar       = $igvVentasTotal - $igvComprasTotal;

        return view('contabilidad.reportes.igv_mensual', compact(
            'filasMensuales', 'igvVentasTotal', 'igvComprasTotal', 'igvAPagar'
        ));
    }

    /**
     * Resumen Gerencial – KPIs financieros integrados del ERP
     */
    public function resumenGerencial()
    {
        // ── Ventas ──────────────────────────────────────────────
        $totalVentas    = Factura::where('Estado_Pago', 'Pagado')->sum('Total');
        $ventasPendientes = Factura::where('Estado_Pago', '!=', 'Pagado')->sum('Total');
        $totalFacturas  = Factura::count();

        // ── Producción ──────────────────────────────────────────
        $proyectosActivos = Proyecto::where('Estado', 'Activo')->count();

        // ── Inventario ──────────────────────────────────────────
        $valorInventario = Inventario::join('productos', 'inventario.Id_Producto', '=', 'productos.Id_Producto')
            ->sum(DB::raw('inventario.Cantidad * productos.Precio_Compra'));

        // ── Contabilidad ────────────────────────────────────────
        $cuentas = CuentaContable::with('detalles')->get();

        $totalIngresos = 0;
        $totalGastos   = 0;
        $totalActivos  = 0;
        $totalPasivos  = 0;
        $totalPatrimonio = 0;

        foreach ($cuentas as $cuenta) {
            $debe  = $cuenta->detalles->sum('Debe');
            $haber = $cuenta->detalles->sum('Haber');

            match ($cuenta->Tipo) {
                'Ingreso'   => $totalIngresos  += ($haber - $debe),
                'Gasto',
                'Costo'     => $totalGastos    += ($debe - $haber),
                'Activo'    => $totalActivos   += ($debe - $haber),
                'Activo (Contra)' => $totalActivos += ($debe - $haber),
                'Pasivo'    => $totalPasivos   += ($haber - $debe),
                'Patrimonio'=> $totalPatrimonio += ($haber - $debe),
                default     => null,
            };
        }

        $utilidadNeta   = $totalIngresos - $totalGastos;
        $margenBruto    = $totalIngresos > 0 ? round(($totalIngresos - collect($cuentas)->where('Tipo', 'Costo')->sum(fn($c) => $c->detalles->sum('Debe') - $c->detalles->sum('Haber'))) / $totalIngresos * 100, 1) : 0;
        $liquidez       = $totalPasivos > 0 ? round($totalActivos / $totalPasivos, 2) : null;
        $endeudamiento  = ($totalActivos + $totalPasivos) > 0 ? round($totalPasivos / ($totalActivos) * 100, 1) : 0;
        $roe            = $totalPatrimonio > 0 ? round($utilidadNeta / $totalPatrimonio * 100, 1) : 0;

        $kpis = [
            ['label' => '💰 Ingresos Totales',      'valor' => 'S/. ' . number_format($totalIngresos, 2),   'estado' => $totalIngresos >= 480000 ? '✅' : '⚠️'],
            ['label' => '💸 Utilidad Neta',          'valor' => 'S/. ' . number_format($utilidadNeta, 2),    'estado' => $utilidadNeta >= 0 ? '✅' : '🔴'],
            ['label' => '📊 Margen Bruto',           'valor' => $margenBruto . '%',                          'estado' => $margenBruto >= 60 ? '✅' : '⚠️'],
            ['label' => '📦 Cuentas por Cobrar',    'valor' => 'S/. ' . number_format($ventasPendientes, 2),'estado' => $ventasPendientes < 200000 ? '✅' : '⚠️'],
            ['label' => '💳 Liquidez Corriente',     'valor' => ($liquidez ? $liquidez . 'x' : 'N/D'),       'estado' => ($liquidez && $liquidez >= 1.5) ? '✅' : '⚠️'],
            ['label' => '📉 Endeudamiento',          'valor' => $endeudamiento . '%',                        'estado' => $endeudamiento < 50 ? '✅' : '⚠️'],
            ['label' => '💼 ROE (Rent. Patrimonio)', 'valor' => $roe . '%',                                  'estado' => $roe >= 15 ? '✅' : '⚠️'],
        ];

        return view('contabilidad.reportes.resumen_gerencial', compact(
            'kpis', 'totalVentas', 'totalFacturas', 'proyectosActivos',
            'valorInventario', 'totalIngresos', 'utilidadNeta',
            'totalActivos', 'totalPasivos', 'totalPatrimonio'
        ));
    }
}
