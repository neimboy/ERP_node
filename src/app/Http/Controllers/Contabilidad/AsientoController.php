<?php

namespace App\Http\Controllers\Contabilidad;

use App\Http\Controllers\Controller;
use App\Models\Asiento;
use App\Models\AsientoDetalle;
use App\Models\Periodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsientoController extends Controller
{
    public function index()
    {
        // Traemos los asientos con sus detalles y cuentas relacionadas (Eager Loading)
        $asientos = Asiento::with('detalles.cuenta', 'periodo')->latest()->get();
        return view('contabilidad.asientos_index', compact('asientos'));
    }

    public function store(Request $request)
    {
        // Lógica simplificada de validación y guardado con Transacciones
        return DB::transaction(function () use ($request) {
            
            // 1. Crear la cabecera del asiento
            $asiento = Asiento::create([
                'Id_Periodo' => $request->Id_Periodo,
                'Fecha' => $request->Fecha,
                'Glosa' => $request->Glosa,
            ]);

            // 2. Registrar los detalles (Viniendo de un array en el formulario)
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
}