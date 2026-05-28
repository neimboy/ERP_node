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
            'DNI'           => ['required', 'string', 'size:8', 'unique:empleados,DNI'],
            'Nombre'        => ['required', 'string', 'max:150', 'regex:/^[a-zA-Z\sñÑáéíóúÁÉÍÓÚ]+$/'],
            'Correo'        => ['required', 'email', 'max:150', 'unique:empleados,Correo', 'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|outlook\.com)$/i'],
            'Telefono'      => ['required', 'string', 'regex:/^9[0-9]{8}$/'], 
            'Fecha_Ingreso' => ['required', 'date'],
        ], [
            'DNI.size'       => 'El DNI debe tener exactamente 8 números.',
            'DNI.unique'     => 'Este DNI ya está registrado.',
            'Nombre.regex'   => 'El nombre no debe contener números ni caracteres especiales.',
            'Correo.regex'   => 'Solo se permiten correos de Gmail o Outlook.',
            'Correo.unique'  => 'Este correo ya está en uso.',
            'Telefono.regex' => 'El teléfono debe iniciar con 9 y tener 9 dígitos.',
        ]);

        // 2. GUARDADO CON PROTECCIÓN
        try {
            \App\Models\Empleado::create([
                'DNI'           => $request->DNI,
                'Nombre'        => $request->Nombre,
                'Correo'        => $request->Correo,
                'Telefono'      => $request->Telefono,
                'Fecha_Ingreso' => $request->Fecha_Ingreso,
                'Estado'        => 1, 
            ]);

            return redirect()->route('rrhh.empleados.index')
                            ->with('success', '¡Empleado guardado exitosamente!');

        } catch (\Exception $e) {

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
            'DNI'           => 'required|string|size:8|unique:empleados,DNI,'.$id.',Id_Empleado',
            'Nombre'        => 'required|string|regex:/^[a-zA-Z\sñÑáéíóúÁÉÍÓÚ]+$/',
            'Correo'        => 'required|email',
            'Telefono'      => 'required|string|regex:/^9[0-9]{8}$/',
            'Fecha_Ingreso' => 'required|date',
        ]);

        $empleado = Empleado::where('Id_Empleado', $id)->firstOrFail();
        $empleado->update($request->all());

        return redirect()->route('rrhh.empleados.index')->with('success', 'Empleado actualizado');
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