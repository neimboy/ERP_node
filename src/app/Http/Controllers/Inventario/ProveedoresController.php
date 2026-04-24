<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedoresController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::all();
        return view('inventarios.proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('inventarios.proveedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'RUC' => 'required|string|max:20|unique:proveedores,RUC',
            'Nombre' => 'required|string|max:150',
            'Telefono' => 'nullable|string|max:20',
        ]);

        Proveedor::create($request->all());
        return redirect()->route('proveedores.index')->with('success', 'Proveedor creado correctamente.');
    }

    public function edit(Proveedor $proveedor)
    {
        return view('inventarios.proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'RUC' => 'required|string|max:20|unique:proveedores,RUC,' . $proveedor->Id_Proveedor . ',Id_Proveedor',
            'Nombre' => 'required|string|max:150',
            'Telefono' => 'nullable|string|max:20',
        ]);

        $proveedor->update($request->all());
        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();
        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado correctamente.');
    }
}

