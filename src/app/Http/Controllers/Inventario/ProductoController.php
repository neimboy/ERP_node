<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        // ✅ Cargamos proveedor y categoría
        $productos = Producto::with(['proveedor', 'categoria'])->get();
        return view('inventarios.productos.index', compact('productos'));
    }

    public function create()
    {
        $proveedores = Proveedor::all();
        $categorias = Categoria::all();
        return view('inventarios.productos.create', compact('proveedores', 'categorias'));
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
        return view('inventarios.productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $proveedores = Proveedor::all();
        $categorias = Categoria::all();
        return view('inventarios.productos.edit', compact('producto', 'proveedores', 'categorias'));
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
