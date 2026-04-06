<?php

namespace App\Http\Controllers\Contabilidad;

use App\Http\Controllers\Controller;
use App\Models\Asiento;
use App\Models\AsientoDetalle;
use App\Models\Periodo;
use App\Models\CuentaContable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsientoController extends Controller
{
    public function index()
    {
        $asientos = Asiento::with('detalles.cuenta', 'periodo')->latest()->get();
        return view('contabilidad.asientos_index', compact('asientos'));
    }

    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {

            $asiento = Asiento::create([
                'Id_Periodo' => $request->Id_Periodo,
                'Fecha' => $request->Fecha,
                'Glosa' => $request->Glosa,
            ]);

            foreach ($request->detalles as $detalle) {
                AsientoDetalle::create([
                    'Id_Asiento' => $asiento->Id_Asiento,
                    'Id_Cuenta' => $detalle['Id_Cuenta'],
                    'Debe' => $detalle['Debe'] ?? 0,
                    'Haber' => $detalle['Haber'] ?? 0,
                ]);
            }

            return redirect()->route('asientos.index')->with('success', 'Asiento contable registrado.');
        });
    }

   
   public function libroMayor(Request $request)
{
    $periodoSeleccionado = $request->Id_Periodo;

    $periodos = Periodo::orderBy('Año', 'desc')
                       ->orderBy('Mes', 'desc')
                       ->get();

    $cuentas = CuentaContable::with(['detalles' => function ($query) use ($periodoSeleccionado) {

        if ($periodoSeleccionado) {
            $query->whereHas('asiento', function ($q) use ($periodoSeleccionado) {
                $q->where('Id_Periodo', $periodoSeleccionado);
            });
        }

        $query->with('asiento')->orderBy('Id_Asiento');
    }])->get();

    return view('contabilidad.libro_mayor', compact('cuentas', 'periodos', 'periodoSeleccionado'));
}

public function estadoResultados(Request $request)
{
    $periodoSeleccionado = $request->Id_Periodo;

    $periodos = \App\Models\Periodo::orderBy('Año', 'desc')
        ->orderBy('Mes', 'desc')
        ->get();

    // INGRESOS
    $ingresos = \App\Models\CuentaContable::where('Tipo', 'Ingreso')
        ->with(['detalles' => function ($query) use ($periodoSeleccionado) {

            if ($periodoSeleccionado) {
                $query->whereHas('asiento', function ($q) use ($periodoSeleccionado) {
                    $q->where('Id_Periodo', $periodoSeleccionado);
                });
            }

        }])->get();

    // GASTOS
    $gastos = \App\Models\CuentaContable::where('Tipo', 'Gasto')
        ->with(['detalles' => function ($query) use ($periodoSeleccionado) {

            if ($periodoSeleccionado) {
                $query->whereHas('asiento', function ($q) use ($periodoSeleccionado) {
                    $q->where('Id_Periodo', $periodoSeleccionado);
                });
            }

        }])->get();

    // Totales
    $totalIngresos = 0;
    foreach ($ingresos as $cuenta) {
        $totalIngresos += $cuenta->detalles->sum('Haber');
    }

    $totalGastos = 0;
    foreach ($gastos as $cuenta) {
        $totalGastos += $cuenta->detalles->sum('Debe');
    }

    $utilidad = $totalIngresos - $totalGastos;

    return view('contabilidad.estado_resultados', compact(
        'ingresos',
        'gastos',
        'totalIngresos',
        'totalGastos',
        'utilidad',
        'periodos',
        'periodoSeleccionado'
    ));
}

public function balanceGeneral(Request $request)
{
    $periodoSeleccionado = $request->Id_Periodo;

    $periodos = \App\Models\Periodo::orderBy('Año', 'desc')
        ->orderBy('Mes', 'desc')
        ->get();

    // ACTIVOS
    $activos = \App\Models\CuentaContable::where('Tipo', 'Activo')
        ->with(['detalles' => function ($query) use ($periodoSeleccionado) {

            if ($periodoSeleccionado) {
                $query->whereHas('asiento', function ($q) use ($periodoSeleccionado) {
                    $q->where('Id_Periodo', $periodoSeleccionado);
                });
            }

        }])->get();

    // PASIVOS
    $pasivos = \App\Models\CuentaContable::where('Tipo', 'Pasivo')
        ->with(['detalles' => function ($query) use ($periodoSeleccionado) {

            if ($periodoSeleccionado) {
                $query->whereHas('asiento', function ($q) use ($periodoSeleccionado) {
                    $q->where('Id_Periodo', $periodoSeleccionado);
                });
            }

        }])->get();

    // PATRIMONIO
    $patrimonio = \App\Models\CuentaContable::where('Tipo', 'Patrimonio')
        ->with(['detalles' => function ($query) use ($periodoSeleccionado) {

            if ($periodoSeleccionado) {
                $query->whereHas('asiento', function ($q) use ($periodoSeleccionado) {
                    $q->where('Id_Periodo', $periodoSeleccionado);
                });
            }

        }])->get();

    // CALCULAR TOTALES
    $totalActivos = 0;
    foreach ($activos as $cuenta) {
        $totalActivos += $cuenta->detalles->sum('Debe') - $cuenta->detalles->sum('Haber');
    }

    $totalPasivos = 0;
    foreach ($pasivos as $cuenta) {
        $totalPasivos += $cuenta->detalles->sum('Haber') - $cuenta->detalles->sum('Debe');
    }

    $totalPatrimonio = 0;
    foreach ($patrimonio as $cuenta) {
        $totalPatrimonio += $cuenta->detalles->sum('Haber') - $cuenta->detalles->sum('Debe');
    }

    return view('contabilidad.balance_general', compact(
        'activos',
        'pasivos',
        'patrimonio',
        'totalActivos',
        'totalPasivos',
        'totalPatrimonio',
        'periodos',
        'periodoSeleccionado'
    ));
}


}