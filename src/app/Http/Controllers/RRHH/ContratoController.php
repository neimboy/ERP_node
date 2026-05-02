<?php

namespace App\Http\Controllers\RRHH;
use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\Empleado;
use App\Models\Puesto;   
use Illuminate\Http\Request;

class ContratoController extends Controller
{
    /**
     * Mostrar la lista de contratos
     */
    public function index()
    {
        $contratos = Contrato::with(['empleado', 'puesto'])->get();
        return view('rrhh.contratos.index', compact('contratos'));
    }

    /**
     * Mostrar el formulario para crear un contrato
     */
    public function create()
    {
        $empleados = Empleado::all();
        $puestos = Puesto::all();
        
        return view('rrhh.contratos.create', compact('empleados', 'puestos'));
    }

    /**
     * Guardar el contrato en la base de datos
     */
    public function store(Request $request)
    {
        $request->validate([
            'Id_Empleado'  => 'required|exists:empleados,Id_Empleado',
            'Id_Puesto'    => 'required|exists:puestos,Id_Puesto',
            'Fecha_Inicio' => 'required|date',
            'Fecha_Fin'    => 'nullable|date|after_or_equal:Fecha_Inicio',
        ]);

        try {
            Contrato::create($request->all());
            return redirect()->route('rrhh.contratos.index')->with('success', 'Contrato creado.');
        } catch (\Exception $e) {
            return dd($e->getMessage()); 
        }
    }

    public function destroy($id)
    {
        try {
            $contrato = Contrato::findOrFail($id);
            $contrato->delete();

            return redirect()->route('rrhh.contratos.index')
                            ->with('success', 'El contrato ha sido eliminado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo eliminar el contrato: ' . $e->getMessage());
        }
    }
}