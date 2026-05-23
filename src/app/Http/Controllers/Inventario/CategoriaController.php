<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Categoria::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('Nombre', 'like', "%{$search}%");
        }

        $categorias = $query->paginate(10);
        return view('inventarios.categorias.index', compact('categorias'));
    }


    public function create()
    {
        return view('inventarios.categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:150|unique:categorias,Nombre',
        ]);

        Categoria::create($request->all());
        return redirect()->route('categorias.index')->with('success', 'Categoría creada correctamente.');
    }

    public function show(Categoria $categoria)
    {
        return view('inventarios.categorias.show', compact('categoria'));
    }

    public function edit(Categoria $categoria)
    {
        return view('inventarios.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'Nombre' => 'required|string|max:150|unique:categorias,Nombre,' . $categoria->Id_Categoria . ',Id_Categoria',
        ]);

        $categoria->update($request->all());
        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada correctamente.');
    }
}
