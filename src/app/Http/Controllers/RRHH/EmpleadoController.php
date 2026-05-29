<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empleado;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::where('Estado', 1)->get();
        return view('rrhh.empleados.index', compact('empleados'));
    }

    public function create()
    {
        return view('rrhh.empleados.create');
    }

public function store(Request $request)
    {
        $request->validate([
            'DNI'           => 'required|unique:empleados,DNI',
            'Nombre'        => 'required|string|max:150',
            'Correo'        => 'nullable|email',
            'Telefono'      => 'nullable|string|max:20',
            'Fecha_Ingreso' => 'required|date',
        ]);

        try { // <--- ¡AQUÍ FALTABA ESTO!
            \App\Models\Empleado::create([
                'DNI'           => $request->DNI,
                'Nombre'        => $request->Nombre,
                'Correo'        => $request->Correo,
                'Telefono'      => $request->Telefono,
                'Fecha_Ingreso' => $request->Fecha_Ingreso,
                'Estado'        => $request->Estado ?? 1,
            ]);

            return redirect()->route('rrhh.empleados.index')
                             ->with('success', '¡Empleado guardado exitosamente!');

        } catch (\Exception $e) { // Ahora este catch ya tiene sentido
            return back()->withInput()->with('error', 'Error en la base de datos: ' . $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        $empleado = Empleado::where('Id_Empleado', $id)->firstOrFail();
        return view('rrhh.empleados.edit', compact('empleado'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'DNI'        => 'required',
            'Nombre'     => 'required',
            'Correo'    => 'nullable|email',
            'Telefono'   => 'nullable|string|max:20',
            'Fecha_Ingreso' => 'required|date',
        ]);

        $empleado = \App\Models\Empleado::where('Id_Empleado', $id)->firstOrFail();
        $empleado->update($request->all());

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado');
    }

    public function destroy(string $id)
    {
        $empleado = Empleado::where('Id_Empleado', $id)->firstOrFail();
        $empleado->Estado = 0;
        $empleado->save();

        return redirect()->route('rrhh.empleados.index')
                        ->with('success', 'Empleado retirado del registro correctamente.');
    }

    public function inactivos()
    {
        $empleados = Empleado::where('Estado', 0)->get();
        return view('rrhh.empleados.index', compact('empleados'));
    }
}