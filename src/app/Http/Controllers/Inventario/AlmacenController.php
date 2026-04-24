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
        // ✅ apunta a resources/views/inventarios/index.blade.php
        return view('inventarios.index', compact('almacenes'));
    }

    public function create()
    {
        // ✅ apunta a resources/views/inventarios/create.blade.php
        return view('inventarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'nullable|string|max:255',
        ]);

        Almacen::create($request->all());
        return redirect()->route('almacenes.index')->with('success', 'Almacén creado correctamente.');
    }

    public function show(Almacen $almacen)
    {
        // ✅ apunta a resources/views/inventarios/show.blade.php
        return view('inventarios.show', compact('almacen'));
    }

    public function edit(Almacen $almacen)
    {
        // ✅ apunta a resources/views/inventarios/edit.blade.php
        return view('inventarios.edit', compact('almacen'));
    }

    public function update(Request $request, Almacen $almacen)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'nullable|string|max:255',
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
