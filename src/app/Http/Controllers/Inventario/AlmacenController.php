<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use Illuminate\Http\Request;

class AlmacenController extends Controller
{
    public function index()
    {
        $almacenes = Almacen::all();
        // ✅ apunta a resources/views/inventarios/almacenes/index.blade.php
        return view('inventarios.almacenes.index', compact('almacenes'));
    }

    public function create()
    {
        // ✅ apunta a resources/views/inventarios/almacenes/create.blade.php
        return view('inventarios.almacenes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:150',
            'Direccion' => 'nullable|string',
        ]);

        Almacen::create($request->all());
        return redirect()->route('almacenes.index')->with('success', 'Almacén creado correctamente.');
    }

    public function show(Almacen $almacen)
    {
        // ✅ apunta a resources/views/inventarios/almacenes/show.blade.php
        return view('inventarios.almacenes.show', compact('almacen'));
    }

    public function edit(Almacen $almacen)
    {
        // ✅ apunta a resources/views/inventarios/almacenes/edit.blade.php
        return view('inventarios.almacenes.edit', compact('almacen'));
    }

    public function update(Request $request, Almacen $almacen)
    {
        $request->validate([
            'Nombre' => 'required|string|max:150',
            'Direccion' => 'nullable|string',
        ]);

        $almacen->update($request->all());
        return redirect()->route('almacenes.index')->with('success', 'Almacén actualizado correctamente.');
    }

    public function destroy(Almacen $almacen)
    {
        $almacen->delete();
        return redirect()->route('almacenes.index')->with('success', 'Almacén eliminado correctamente.');
    }
}
