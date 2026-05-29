<?php

namespace App\Http\Controllers\Contabilidad;

use App\Http\Controllers\Controller;
use App\Models\Contabilidad\Asiento;
use App\Models\Contabilidad\AsientoDetalle;
use App\Models\Contabilidad\CuentaContable;
use App\Models\Contabilidad\Periodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsientoController extends Controller
{
    // =========================================================================
    // CRUD — Asientos contables
    // =========================================================================

    /**
     * Listado de asientos con filtros por período y búsqueda.
     * GET /contabilidad/asientos
     */
    public function index(Request $request)
    {
        $query = Asiento::with(['periodo', 'detalles.cuenta'])
            ->orderBy('Fecha', 'desc');

        if ($request->filled('periodo')) {
            $query->where('Id_Periodo', $request->periodo);
        }

        if ($request->filled('buscar')) {
            $query->where('Glosa', 'like', '%' . $request->buscar . '%');
        }

        $asientos = $query->paginate(20)->withQueryString();
        $periodos = Periodo::orderBy('Año', 'desc')->orderBy('Mes', 'desc')->get();
        $cuentas  = CuentaContable::orderBy('Codigo')->get();

        return view('contabilidad.asientos.index', compact('asientos', 'periodos', 'cuentas'));
    }

    /**
     * Formulario para crear un nuevo asiento.
     * GET /contabilidad/asientos/create
     */
    public function create()
    {
        $periodos = Periodo::where('Estado', 'Abierto')
            ->orderBy('Año', 'desc')
            ->orderBy('Mes', 'desc')
            ->get();

        $cuentas = CuentaContable::orderBy('Codigo')->get();

        return view('contabilidad.asientos.create', compact('periodos', 'cuentas'));
    }

    /**
     * Guardar un nuevo asiento con sus líneas.
     * POST /contabilidad/asientos
     */
    public function store(Request $request)
    {
        $request->validate([
            'Id_Periodo'          => 'required|exists:periodos,Id_Periodo',
            'Fecha'               => 'required|date',
            'Glosa'               => 'nullable|string|max:255',
            'detalles'            => 'required|array|min:2',
            'detalles.*.Id_Cuenta'=> 'required|exists:cuenta_contable,Id_Cuenta',
            'detalles.*.Debe'     => 'required|numeric|min:0',
            'detalles.*.Haber'    => 'required|numeric|min:0',
        ]);

        // Verificar período abierto
        $periodo = Periodo::findOrFail($request->Id_Periodo);
        if ($periodo->Estado !== 'Abierto') {
            return back()->withInput()
                ->with('error', "El período {$periodo->label} está cerrado. No se pueden registrar asientos.");
        }

        // Validar partida doble
        $totalDebe  = collect($request->detalles)->sum('Debe');
        $totalHaber = collect($request->detalles)->sum('Haber');

        if (abs($totalDebe - $totalHaber) > 0.01) {
            return back()->withInput()
                ->with('error', "El asiento no cuadra: Debe ({$totalDebe}) ≠ Haber ({$totalHaber}).");
        }

        DB::transaction(function () use ($request) {
            $asiento = Asiento::create([
                'Id_Periodo' => $request->Id_Periodo,
                'Fecha'      => $request->Fecha,
                'Glosa'      => $request->Glosa,
            ]);

            foreach ($request->detalles as $linea) {
                if ((float)$linea['Debe'] > 0 || (float)$linea['Haber'] > 0) {
                    AsientoDetalle::create([
                        'Id_Asiento' => $asiento->Id_Asiento,
                        'Id_Cuenta'  => $linea['Id_Cuenta'],
                        'Debe'       => round((float)$linea['Debe'],  2),
                        'Haber'      => round((float)$linea['Haber'], 2),
                    ]);
                }
            }
        });

        return redirect()->route('asientos.index')
            ->with('success', 'Asiento contable registrado correctamente.');
    }

    /**
     * Ver detalle de un asiento.
     * GET /contabilidad/asientos/{asiento}
     */
    public function show(Asiento $asiento)
    {
        $asiento->load(['periodo', 'detalles.cuenta']);

        $totalDebe  = $asiento->detalles->sum('Debe');
        $totalHaber = $asiento->detalles->sum('Haber');

        return view('contabilidad.asientos.show', compact('asiento', 'totalDebe', 'totalHaber'));
    }

    /**
     * Formulario para editar un asiento existente.
     * GET /contabilidad/asientos/{asiento}/edit
     */
    public function edit(Asiento $asiento)
    {
        $asiento->load(['periodo', 'detalles.cuenta']);

        // No editar asientos de períodos cerrados
        if ($asiento->periodo->Estado !== 'Abierto') {
            return redirect()->route('asientos.index')
                ->with('error', "No se puede editar un asiento de un período cerrado.");
        }

        $periodos = Periodo::where('Estado', 'Abierto')
            ->orderBy('Año', 'desc')
            ->orderBy('Mes', 'desc')
            ->get();

        $cuentas = CuentaContable::orderBy('Codigo')->get();

        return view('contabilidad.asientos.edit', compact('asiento', 'periodos', 'cuentas'));
    }

    /**
     * Actualizar asiento y sus líneas.
     * PUT /contabilidad/asientos/{asiento}
     */
    public function update(Request $request, Asiento $asiento)
    {
        $request->validate([
            'Id_Periodo'          => 'required|exists:periodos,Id_Periodo',
            'Fecha'               => 'required|date',
            'Glosa'               => 'nullable|string|max:255',
            'detalles'            => 'required|array|min:2',
            'detalles.*.Id_Cuenta'=> 'required|exists:cuenta_contable,Id_Cuenta',
            'detalles.*.Debe'     => 'required|numeric|min:0',
            'detalles.*.Haber'    => 'required|numeric|min:0',
        ]);

        $periodo = Periodo::findOrFail($request->Id_Periodo);
        if ($periodo->Estado !== 'Abierto') {
            return back()->withInput()
                ->with('error', "El período {$periodo->label} está cerrado.");
        }

        $totalDebe  = collect($request->detalles)->sum('Debe');
        $totalHaber = collect($request->detalles)->sum('Haber');

        if (abs($totalDebe - $totalHaber) > 0.01) {
            return back()->withInput()
                ->with('error', "El asiento no cuadra: Debe ({$totalDebe}) ≠ Haber ({$totalHaber}).");
        }

        DB::transaction(function () use ($request, $asiento) {
            $asiento->update([
                'Id_Periodo' => $request->Id_Periodo,
                'Fecha'      => $request->Fecha,
                'Glosa'      => $request->Glosa,
            ]);

            // Reemplazar líneas
            $asiento->detalles()->delete();

            foreach ($request->detalles as $linea) {
                if ((float)$linea['Debe'] > 0 || (float)$linea['Haber'] > 0) {
                    AsientoDetalle::create([
                        'Id_Asiento' => $asiento->Id_Asiento,
                        'Id_Cuenta'  => $linea['Id_Cuenta'],
                        'Debe'       => round((float)$linea['Debe'],  2),
                        'Haber'      => round((float)$linea['Haber'], 2),
                    ]);
                }
            }
        });

        return redirect()->route('asientos.index')
            ->with('success', 'Asiento actualizado correctamente.');
    }

    /**
     * Eliminar un asiento (solo si el período está abierto).
     * DELETE /contabilidad/asientos/{asiento}
     */
    public function destroy(Asiento $asiento)
    {
        $asiento->load('periodo');

        if ($asiento->periodo->Estado !== 'Abierto') {
            return back()->with('error', 'No se puede eliminar un asiento de un período cerrado.');
        }

        DB::transaction(function () use ($asiento) {
            $asiento->detalles()->delete();
            $asiento->delete();
        });

        return redirect()->route('asientos.index')
            ->with('success', 'Asiento eliminado correctamente.');
    }

    // =========================================================================
    // REPORTES CONTABLES
    // =========================================================================

    /**
     * Libro Mayor — saldos acumulados por cuenta contable.
     * GET /contabilidad/libro-mayor
     */
    public function libroMayor(Request $request)
    {
        $cuentas  = CuentaContable::orderBy('Codigo')->get();
        $periodos = Periodo::orderBy('Año', 'desc')->orderBy('Mes', 'desc')->get();

        $query = AsientoDetalle::with(['cuenta', 'asiento']);

        if ($request->filled('Id_Periodo')) {
            $query->whereHas('asiento', fn($q) => $q->where('Id_Periodo', $request->Id_Periodo));
        }
        if ($request->filled('fecha_inicio')) {
            $query->whereHas('asiento', fn($q) => $q->where('Fecha', '>=', $request->fecha_inicio));
        }
        if ($request->filled('fecha_fin')) {
            $query->whereHas('asiento', fn($q) => $q->where('Fecha', '<=', $request->fecha_fin));
        }

        $detalles = $query->get();

        // Agrupar por cuenta y calcular totales — estructura que espera la vista
        $cuentasMayor = $cuentas->map(function ($cuenta) use ($detalles) {
            $lineas     = $detalles->where('Id_Cuenta', $cuenta->Id_Cuenta);
            $totalDebe  = $lineas->sum('Debe');
            $totalHaber = $lineas->sum('Haber');

            return [
                'codigo'         => $cuenta->Codigo,
                'nombre'         => $cuenta->Nombre_Cuenta,
                'tipo'           => $cuenta->Tipo,
                'total_debe'     => $totalDebe,
                'total_haber'    => $totalHaber,
                'saldo_deudor'   => max($totalDebe - $totalHaber, 0),
                'saldo_acreedor' => max($totalHaber - $totalDebe, 0),
            ];
        })->sortBy('codigo')->values();

        return view('contabilidad.reportes.libro_mayor', compact(
            'cuentas', 'periodos', 'cuentasMayor'
        ));
    }

    /**
     * Estado de Resultados — ingresos vs gastos del período.
     * GET /contabilidad/estado-resultados
     */
    public function estadoResultados(Request $request)
    {
        $periodos = Periodo::orderBy('Año', 'desc')->orderBy('Mes', 'desc')->get();

        // Valores por defecto (sin filtro, muestra todo acumulado)
        $movimientos = AsientoDetalle::with('cuenta')->get();

        if ($request->filled('Id_Periodo')) {
            $movimientos = AsientoDetalle::with('cuenta')
                ->whereHas('asiento', fn($q) => $q->where('Id_Periodo', $request->Id_Periodo))
                ->get();
        }

        // detalleIngresos — estructura que usa la vista: [codigo, nombre, monto]
        $detalleIngresos = $movimientos
            ->filter(fn($d) => $d->cuenta && $d->cuenta->Tipo === 'Ingreso')
            ->groupBy('Id_Cuenta')
            ->map(fn($grupo) => [
                'codigo' => $grupo->first()->cuenta->Codigo,
                'nombre' => $grupo->first()->cuenta->Nombre_Cuenta,
                'monto'  => $grupo->sum('Haber') - $grupo->sum('Debe'),
            ])
            ->filter(fn($i) => $i['monto'] > 0)
            ->values();

        // detalleGastos — estructura que usa la vista: [codigo, nombre, monto]
        $detalleGastos = $movimientos
            ->filter(fn($d) => $d->cuenta && in_array($d->cuenta->Tipo, ['Gasto', 'Costo']))
            ->groupBy('Id_Cuenta')
            ->map(fn($grupo) => [
                'codigo' => $grupo->first()->cuenta->Codigo,
                'nombre' => $grupo->first()->cuenta->Nombre_Cuenta,
                'monto'  => $grupo->sum('Debe') - $grupo->sum('Haber'),
            ])
            ->filter(fn($g) => $g['monto'] > 0)
            ->values();

        $ingresos     = $detalleIngresos->sum('monto');
        $gastos       = $detalleGastos->sum('monto');
        $utilidadNeta = $ingresos - $gastos;

        return view('contabilidad.reportes.estado_resultados', compact(
            'periodos', 'detalleIngresos', 'detalleGastos',
            'ingresos', 'gastos', 'utilidadNeta'
        ));
    }

    /**
     * Balance General — activos, pasivos y patrimonio acumulado.
     * GET /contabilidad/balance-general
     */
    public function balanceGeneral(Request $request)
    {
        $periodos = Periodo::orderBy('Año', 'desc')->orderBy('Mes', 'desc')->get();

        // Acumular todos los asientos (o hasta el período seleccionado)
        $query = AsientoDetalle::with('cuenta');

        if ($request->filled('Id_Periodo')) {
            $periodo    = Periodo::findOrFail($request->Id_Periodo);
            $fechaCorte = sprintf('%04d-%02d-28', $periodo->Año, $periodo->Mes);
            $query->whereHas('asiento', fn($q) => $q->where('Fecha', '<=', $fechaCorte));
        }

        $movimientos = $query->get();

        // Helper: agrupar por tipo y devolver [codigo, nombre, monto]
        $agrupar = fn(string $tipo) => $movimientos
            ->filter(fn($d) => $d->cuenta && $d->cuenta->Tipo === $tipo)
            ->groupBy('Id_Cuenta')
            ->map(fn($grupo) => [
                'codigo' => $grupo->first()->cuenta->Codigo,
                'nombre' => $grupo->first()->cuenta->Nombre_Cuenta,
                'monto'  => $grupo->sum('Debe') - $grupo->sum('Haber'),
            ])
            ->values();

        $activos    = $agrupar('Activo');
        $pasivos    = $agrupar('Pasivo');
        $patrimonio = $agrupar('Patrimonio');

        $totalActivo          = $activos->sum('monto');
        $totalPasivo          = $pasivos->sum('monto');
        $totalPatrimonio      = $patrimonio->sum('monto');
        $totalPasivoYPatrimonio = $totalPasivo + $totalPatrimonio;

        return view('contabilidad.reportes.balance_general', compact(
            'periodos', 'activos', 'pasivos', 'patrimonio',
            'totalActivo', 'totalPasivo', 'totalPatrimonio', 'totalPasivoYPatrimonio'
        ));
    }

    /**
     * IGV Mensual — crédito fiscal vs débito fiscal por período.
     * GET /contabilidad/igv-mensual
     */
    public function igvMensual(Request $request)
    {
        $periodos = Periodo::orderBy('Año', 'desc')->orderBy('Mes', 'desc')->get();

        // Cuenta 40 = IGV
        $cuentaIgv = CuentaContable::where('Codigo', '40')->first();

        $filasMensuales  = [];
        $igvVentasTotal  = 0;
        $igvComprasTotal = 0;

        foreach ($periodos as $periodo) {
            if (! $cuentaIgv) continue;

            $movs = AsientoDetalle::where('Id_Cuenta', $cuentaIgv->Id_Cuenta)
                ->whereHas('asiento', fn($q) => $q->where('Id_Periodo', $periodo->Id_Periodo))
                ->get();

            $igvVentas  = $movs->sum('Haber'); // IGV ventas → haber de cta 40
            $igvCompras = $movs->sum('Debe');  // IGV compras → debe de cta 40
            $igvNeto    = $igvVentas - $igvCompras;

            if ($igvVentas == 0 && $igvCompras == 0) continue;

            $filasMensuales[] = [
                'label'      => ($periodo->label ?? ($periodo->Año . '-' . str_pad($periodo->Mes, 2, '0', STR_PAD_LEFT))),
                'igv_ventas' => $igvVentas,
                'igv_compras'=> $igvCompras,
                'igv_neto'   => $igvNeto,
            ];

            $igvVentasTotal  += $igvVentas;
            $igvComprasTotal += $igvCompras;
        }

        $igvAPagar = $igvVentasTotal - $igvComprasTotal;

        return view('contabilidad.reportes.igv_mensual', compact(
            'periodos', 'filasMensuales',
            'igvVentasTotal', 'igvComprasTotal', 'igvAPagar'
        ));
    }

    /**
     * Estado de Resultados Semestral — comparativo mes a mes.
     * GET /contabilidad/estado-resultados-semestral
     */
    public function estadoResultadosSemestral(Request $request)
    {
        $anioActual = $request->input('anio', now()->year);
        $anios      = Periodo::selectRaw('Año')->distinct()->orderBy('Año', 'desc')->pluck('Año');

        $periodos = Periodo::where('Año', $anioActual)->orderBy('Mes')->get();

        $meses               = [];
        $totalIngresos       = 0;
        $totalCostos         = 0;
        $totalGastos         = 0;
        $totalUtilidadBruta  = 0;
        $totalUtilidadOperativa = 0;

        foreach ($periodos as $periodo) {
            $movs = AsientoDetalle::with('cuenta')
                ->whereHas('asiento', fn($q) => $q->where('Id_Periodo', $periodo->Id_Periodo))
                ->get();

            $ingresos = $movs->filter(fn($d) => $d->cuenta && $d->cuenta->Tipo === 'Ingreso')->sum('Haber');
            $costos   = $movs->filter(fn($d) => $d->cuenta && $d->cuenta->Tipo === 'Costo')->sum('Debe');
            $gastos   = $movs->filter(fn($d) => $d->cuenta && $d->cuenta->Tipo === 'Gasto')->sum('Debe');

            $utilidadBruta    = $ingresos - $costos;
            $utilidadOperativa = $utilidadBruta - $gastos;
            $margenBruto      = $ingresos > 0 ? round(($utilidadBruta / $ingresos) * 100, 1) : 0;
            $margenOperativo  = $ingresos > 0 ? round(($utilidadOperativa / $ingresos) * 100, 1) : 0;

            $meses[] = [
                'label'              => $periodo->label ?? ($periodo->Año . '-' . str_pad($periodo->Mes, 2, '0', STR_PAD_LEFT)),
                'ingresos'           => $ingresos,
                'costos'             => $costos,
                'gastos'             => $gastos,
                'utilidad_bruta'     => $utilidadBruta,
                'margen_bruto'       => $margenBruto,
                'utilidad_operativa' => $utilidadOperativa,
                'margen_operativo'   => $margenOperativo,
            ];

            $totalIngresos          += $ingresos;
            $totalCostos            += $costos;
            $totalGastos            += $gastos;
            $totalUtilidadBruta     += $utilidadBruta;
            $totalUtilidadOperativa += $utilidadOperativa;
        }

        $margenBrutoTotal     = $totalIngresos > 0 ? round(($totalUtilidadBruta / $totalIngresos) * 100, 1) : 0;
        $margenOperativoTotal = $totalIngresos > 0 ? round(($totalUtilidadOperativa / $totalIngresos) * 100, 1) : 0;

        return view('contabilidad.reportes.estado_resultados_semestral', compact(
            'meses', 'anios', 'anioActual',
            'totalIngresos', 'totalCostos', 'totalGastos',
            'totalUtilidadBruta', 'totalUtilidadOperativa',
            'margenBrutoTotal', 'margenOperativoTotal'
        ));
    }

    /**
     * Resumen Gerencial — KPIs de alto nivel para la gerencia.
     * GET /contabilidad/resumen-gerencial
     */
    public function resumenGerencial(Request $request)
    {
        $anioActual = $request->input('anio', now()->year);
        $anios      = Periodo::selectRaw('Año')->distinct()->orderBy('Año', 'desc')->pluck('Año');

        // ── Ingresos y gastos del año ──────────────────────────────────────
        $movAnio = AsientoDetalle::with('cuenta')
            ->whereHas('asiento.periodo', fn($q) => $q->where('Año', $anioActual))
            ->get();

        $ingresosAnuales = $movAnio->filter(fn($d) => $d->cuenta && $d->cuenta->Tipo === 'Ingreso')->sum('Haber');
        $gastosAnuales   = $movAnio->filter(fn($d) => $d->cuenta && in_array($d->cuenta->Tipo, ['Gasto', 'Costo']))->sum('Debe');
        $utilidadAnual   = $ingresosAnuales - $gastosAnuales;
        $margen          = $ingresosAnuales > 0 ? round(($utilidadAnual / $ingresosAnuales) * 100, 2) : 0;

        // ── Saldo de caja (cuenta 10) ─────────────────────────────────────
        $cuentaCaja = CuentaContable::where('Codigo', '10')->first();
        $saldoCaja  = 0;
        if ($cuentaCaja) {
            $movCaja   = AsientoDetalle::where('Id_Cuenta', $cuentaCaja->Id_Cuenta)->get();
            $saldoCaja = $movCaja->sum('Debe') - $movCaja->sum('Haber');
        }

        // ── Balance acumulado ─────────────────────────────────────────────
        $todosMovs = AsientoDetalle::with('cuenta')->get();

        $calcSaldo = fn(string $tipo) => $todosMovs
            ->filter(fn($d) => $d->cuenta && $d->cuenta->Tipo === $tipo)
            ->pipe(fn($col) => $col->sum('Debe') - $col->sum('Haber'));

        $totalActivos    = $calcSaldo('Activo');
        $totalPasivos    = abs($calcSaldo('Pasivo'));
        $totalPatrimonio = abs($calcSaldo('Patrimonio'));

        // ── Datos cruzados de otros módulos ──────────────────────────────
        $totalVentas     = DB::table('pagos')->sum('Monto');
        $totalFacturas   = DB::table('facturas')->count();
        $proyectosActivos = DB::table('proyectos')->where('Estado', 'en_progreso')->count();
        $valorInventario = DB::table('inventario')
            ->join('productos', 'inventario.Id_Producto', '=', 'productos.Id_Producto')
            ->selectRaw('SUM(inventario.Cantidad * productos.Precio_Compra) as total')
            ->value('total') ?? 0;

        // ── KPIs para las tarjetas ─────────────────────────────────────────
        $kpis = [
            ['label' => 'Ingresos del Año',   'valor' => 'S/. ' . number_format($ingresosAnuales, 2), 'estado' => '💰'],
            ['label' => 'Gastos del Año',     'valor' => 'S/. ' . number_format($gastosAnuales, 2),   'estado' => '💸'],
            ['label' => 'Utilidad Neta',      'valor' => 'S/. ' . number_format($utilidadAnual, 2),   'estado' => $utilidadAnual >= 0 ? '✅' : '🔴'],
            ['label' => 'Margen Neto',        'valor' => $margen . '%',                                'estado' => $margen >= 15 ? '📈' : '📉'],
            ['label' => 'Saldo en Caja',      'valor' => 'S/. ' . number_format($saldoCaja, 2),       'estado' => '🏦'],
            ['label' => 'Proyectos Activos',  'valor' => $proyectosActivos . ' proyectos',             'estado' => '🏗️'],
            ['label' => 'Valor Inventario',   'valor' => 'S/. ' . number_format($valorInventario, 2), 'estado' => '📦'],
            ['label' => 'Facturas Emitidas',  'valor' => $totalFacturas . ' facturas',                 'estado' => '🧾'],
        ];

        return view('contabilidad.reportes.resumen_gerencial', compact(
            'anios', 'anioActual', 'kpis',
            'totalVentas', 'totalFacturas', 'proyectosActivos', 'valorInventario',
            'totalActivos', 'totalPasivos', 'totalPatrimonio'
        ));
    }
}