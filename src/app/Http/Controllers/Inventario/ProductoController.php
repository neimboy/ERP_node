<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
<<<<<<< HEAD
        $productos = Producto::all();
        return view('inventarios.index', compact('productos'));
=======
        $query = Producto::with(['proveedor', 'categoria']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('Nombre', 'like', "%{$search}%")
                ->orWhere('Codigo', 'like', "%{$search}%");
        }

        $productos = $query->paginate(10);
        return view('inventarios.productos.index', compact('productos'));
>>>>>>> origin/main
    }

    public function create()
    {
<<<<<<< HEAD
        return view('inventarios.create');
=======
        $proveedores = Proveedor::all();
        $categorias = Categoria::all();
        return view('inventarios.productos.create', compact('proveedores', 'categorias'));
>>>>>>> origin/main
    }

    public function store(Request $request)
    {
        $request->validate([
            'Codigo' => 'required|string|max:50|unique:productos,Codigo',
            'Nombre' => 'required|string|max:150',
            'Precio_Compra' => 'nullable|numeric',
            'Precio_Venta' => 'nullable|numeric',
            'Id_Proveedor' => 'required|exists:proveedores,Id_Proveedor',
            'Id_Categoria' => 'required|exists:categorias,Id_Categoria',
        ]);

        Producto::create($request->all());
        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
    }

    public function show(Producto $producto)
    {
<<<<<<< HEAD
        return view('inventarios.show', compact('producto'));
=======
        return view('inventarios.productos.show', compact('producto'));
>>>>>>> origin/main
    }

    public function edit(Producto $producto)
    {
<<<<<<< HEAD
        return view('inventarios.edit', compact('producto'));
=======
        $proveedores = Proveedor::all();
        $categorias = Categoria::all();
        return view('inventarios.productos.edit', compact('producto', 'proveedores', 'categorias'));
>>>>>>> origin/main
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'Codigo' => 'required|string|max:50|unique:productos,Codigo,' . $producto->Id_Producto . ',Id_Producto',
            'Nombre' => 'required|string|max:150',
            'Precio_Compra' => 'nullable|numeric',
            'Precio_Venta' => 'nullable|numeric',
            'Id_Proveedor' => 'required|exists:proveedores,Id_Proveedor',
            'Id_Categoria' => 'required|exists:categorias,Id_Categoria',
        ]);

        $producto->update($request->all());
        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente.');
    }
}
