<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nomina;
use App\Models\Empleado;
use App\Models\Periodo;

class NominaController extends Controller
{
    public function index()
    {
        // Cargamos las relaciones para evitar lentitud (Eager Loading)
        $nominas = Nomina::with(['empleado', 'periodo'])->get();
        return view('rrhh.nominas.index', compact('nominas'));
    }

    public function create()
    {
        $empleados = Empleado::all();
        $periodos = Periodo::all();
        return view('rrhh.nominas.create', compact('empleados', 'periodos'));
    }

    public function store(Request $request)
    {
        // 1. Validamos que lleguen todos los campos que tiene tu formulario
        $request->validate([
            'Id_Empleado'       => 'required',
            'Id_Periodo'        => 'required',
            'Total_Bruto'       => 'required|numeric',
            'Total_Deducciones' => 'required|numeric',
            'Neto_Pagar'        => 'required|numeric',
        ]);

        try {
            // 2. Insertamos directamente los valores que vienen del formulario
            \DB::table('nominas')->insert([
                'Id_Empleado'       => $request->Id_Empleado,
                'Id_Periodo'        => $request->Id_Periodo,
                'Total_Bruto'       => $request->Total_Bruto,
                'Total_Deducciones' => $request->Total_Deducciones,
                'Neto_Pagar'        => $request->Neto_Pagar,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            return redirect()->route('rrhh.nominas.index')->with('success', 'Nómina guardada exitosamente');

        } catch (\Exception $e) {
            // Si hay un error de base de datos (como una columna mal escrita), lo veremos aquí
            return back()->withInput()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $nomina = Nomina::findOrFail($id);
        $nomina->delete();
        return redirect()->route('rrhh.nominas.index')->with('success', 'Registro eliminado.');
    }
}
