<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empleado;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empleados = \App\Models\Empleado::all();
        return view('rrhh.empleados.index', compact('empleados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rrhh.empleados.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'DNI'           => 'required|unique:empleados,DNI',
        'Nombre_Empleado'        => 'required|string|max:150',
        'Fecha_Ingreso' => 'required|date',
        'Correo_Empleado'        => 'nullable|email',
    ]);
    //  Guardar en la base de datos usando el Modelo
    \App\Models\Empleado::create([
        'DNI'             => $request->DNI,
        'Nombre' => $request->Nombre,
        'Correo' => $request->Correo,
        'Telefono'        => $request->Telefono,
        'Fecha_Ingreso'   => $request->Fecha_Ingreso,
        'Estado'          => 1, // Lo creamos como activo por defecto
    ]);

    return redirect()->route('rrhh.empleados.index')->with('success', '¡Empleado guardado exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('rrhh.empleados.edit', compact('empleado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'DNI' => 'required',
            'Nombre' => 'required',
            'Correo' => 'required|email',
            'Telefono' => 'required',
            'Fecha_Ingreso' => 'required|date',
    ]);

    $empleado = \App\Models\Empleado::where('Id_Empleado', $id)->firstOrFail();
    $empleado->update($request->all());

    return redirect()->route('rrhh.empleados.index')->with('success', 'Empleado actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $empleado = \App\Models\Empleado::where('Id_Empleado', $id)->firstOrFail();
        $empleado->delete();

        return redirect()->route('rrhh.empleados.index')->with('success', 'Empleado eliminado correctamente');
    }
}
