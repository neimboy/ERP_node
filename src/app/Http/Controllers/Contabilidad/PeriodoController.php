<?php

namespace App\Http\Controllers\Contabilidad;

use App\Http\Controllers\Controller;
use App\Models\Contabilidad\Periodo;
use Illuminate\Http\Request;

class PeriodoController extends Controller
{
    public function index()
    {
        $periodos = Periodo::withCount('asientos')
            ->orderBy('Año', 'desc')
            ->orderBy('Mes', 'desc')
            ->get();

        return view('contabilidad.periodos.index', compact('periodos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Año' => 'required|integer|min:2000|max:2100',
            'Mes' => 'required|integer|min:1|max:12',
        ]);

        // Evitar duplicados
        $existe = Periodo::where('Año', $request->Año)
            ->where('Mes', $request->Mes)
            ->exists();

        if ($existe) {
            return back()->with('error', "El período {$request->Mes}/{$request->Año} ya existe.");
        }

        Periodo::create([
            'Año'    => $request->Año,
            'Mes'    => $request->Mes,
            'Estado' => 'Abierto',
        ]);

        return back()->with('success', "Período {$request->Mes}/{$request->Año} creado correctamente.");
    }

    /**
     * Abre o cierra un período contable.
     */
    public function toggleEstado(Periodo $periodo)
    {
        // No se puede cerrar si tiene asientos descuadrados
        if ($periodo->Estado === 'Abierto') {
            $asientosDescuadrados = $periodo->asientos()
                ->with('detalles')
                ->get()
                ->filter(function ($a) {
                    return abs($a->detalles->sum('Debe') - $a->detalles->sum('Haber')) > 0.01;
                });

            if ($asientosDescuadrados->count() > 0) {
                return back()->with('error',
                    "No se puede cerrar el período: tiene {$asientosDescuadrados->count()} asiento(s) descuadrado(s)."
                );
            }

            $periodo->update(['Estado' => 'Cerrado']);
            return back()->with('success', "Período {$periodo->label} cerrado correctamente.");
        }

        $periodo->update(['Estado' => 'Abierto']);
        return back()->with('success', "Período {$periodo->label} reabierto correctamente.");
    }

    public function destroy(Periodo $periodo)
    {
        if ($periodo->asientos()->exists()) {
            return back()->with('error',
                "No se puede eliminar el período {$periodo->label}: tiene asientos registrados."
            );
        }

        $periodo->delete();
        return back()->with('success', "Período {$periodo->label} eliminado.");
    }
}