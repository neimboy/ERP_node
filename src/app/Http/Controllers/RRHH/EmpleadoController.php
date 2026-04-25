<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empleado;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::all();
        return view('rrhh.empleados.index', compact('empleados'));
    }

    public function create()
    {
        return view('rrhh.empleados.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'DNI'             => 'required|unique:empleados,DNI',
            'Nombre_Empleado'  => 'required|string|max:150',
            'Correo_Empleado' => 'nullable|email',
            'Telefono'        => 'nullable|string|max:20',
            'Fecha_Ingreso'   => 'required|date',
        ]);

        \App\Models\Empleado::create([
            'DNI'           => $request->DNI,
            'Nombre_Empleado'=> $request->Nombre_Empleado,
            'Correo_Empleado'=> $request->Correo_Empleado,
            'Telefono'       => $request->Telefono,
            'Fecha_Ingreso'  => $request->Fecha_Ingreso,
            'Estado'        => $request->Estado ?? 1,
        ]);

        return redirect()->route('rrhh.empleados.index')->with('success', '¡Empleado guardado exitosamente!');
    }

    public function edit(string $id)
    {
        $empleado = Empleado::where('Id_Empleado', $id)->firstOrFail();
        return view('rrhh.empleados.edit', compact('empleado'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'DNI'             => 'required',
            'Nombre_Empleado' => 'required',
            'Correo_Empleado' => 'nullable|email',
            'Telefono'        => 'nullable|string|max:20',
            'Fecha_Ingreso'   => 'required|date',
        ]);

        $empleado = \App\Models\Empleado::where('Id_Empleado', $id)->firstOrFail();
        $empleado->update($request->all());

        return redirect()->route('rrhh.empleados.index')->with('success', 'Empleado actualizado');
    }

    public function destroy(string $id)
    {
        $empleado = Empleado::where('Id_Empleado', $id)->firstOrFail();
        $empleado->delete();

        return redirect()->route('rrhh.empleados.index')->with('success', 'Empleado eliminado correctamente');
    }
}