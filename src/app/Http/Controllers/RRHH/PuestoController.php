<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Puesto;
use Illuminate\Http\Request;

class PuestoController extends Controller
{
    // Mostrar lista de puestos
    public function index()
    {
        $puestos = Puesto::all();
        return view('rrhh.puestos.index', compact('puestos'));
    }

    // Formulario para crear puesto
    public function create()
    {
        return view('rrhh.puestos.create');
    }

    // Guardar el puesto en la BD
    public function store(Request $request)
    {
        $request->validate([
            'Nombre_Puesto' => 'required|string|max:150',
            'Salario_Base'  => 'required|numeric|min:0',
        ]);

        Puesto::create($request->all());

        return redirect()->route('rrhh.puestos.index')
            ->with('success', 'Puesto creado con éxito.');
    }

    // Formulario para editar
    public function edit($id)
    {
        // Buscamos por Id_Puesto explícitamente
        $puesto = Puesto::findOrFail($id);
        return view('rrhh.puestos.edit', compact('puesto'));
    }

    // Actualizar datos
    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre_Puesto' => 'required|string|max:150',
            'Salario_Base'  => 'required|numeric|min:0',
        ]);

        $puesto = Puesto::findOrFail($id);
        $puesto->update($request->all());

        return redirect()->route('rrhh.puestos.index')
            ->with('success', 'Puesto actualizado correctamente.');
    }

    // Eliminar puesto
    public function destroy($id)
    {
        $puesto = Puesto::findOrFail($id);
        $puesto->delete();

        return redirect()->route('rrhh.puestos.index')
            ->with('success', 'Puesto eliminado.');
    }
}