<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\OrdenCompra;
use App\Models\Proveedor;
use App\Models\Almacen;
use App\Models\Producto;
use Illuminate\Http\Request;

class ComprasController extends Controller
{
    // Listado de compras
    public function index(Request $request)
    {
        $query = OrdenCompra::with(['proveedor', 'almacen', 'detalles.producto']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('proveedor', function($q) use ($search) {
                $q->where('Nombre', 'like', "%{$search}%");
            })->orWhere('Fecha', 'like', "%{$search}%");
        }

        $compras = $query->paginate(10);
        return view('inventarios.compras.index', compact('compras'));
    }

    // Formulario de nueva compra
    public function create()
    {
        $proveedores = Proveedor::all();
        $almacenes   = Almacen::all();
        $productos   = Producto::all();

        return view('inventarios.compras.create', compact('proveedores', 'almacenes', 'productos'));
    }

    // Guardar nueva compra
    public function store(Request $request)
    {
        // Validación básica
        $request->validate([
            'Id_Proveedor' => 'required|exists:proveedores,Id_Proveedor',
            'Id_Almacen'   => 'required|exists:almacenes,Id_Almacen',
            'Fecha'        => 'required|date',
            'productos'    => 'required|array',
            'productos.*.Id_Producto' => 'required|exists:productos,Id_Producto',
            'productos.*.Cantidad'    => 'required|integer|min:1',
        ]);

        // Crear la orden de compra
        $compra = OrdenCompra::create([
            'Id_Proveedor' => $request->Id_Proveedor,
            'Id_Almacen'   => $request->Id_Almacen,
            'Fecha'        => $request->Fecha,
            'Estado'       => 'Pendiente',
        ]);

        // Guardar los detalles
        foreach ($request->productos as $p) {
            $producto = Producto::findOrFail($p['Id_Producto']);

            $compra->detalles()->create([
                'Id_Producto' => $p['Id_Producto'],
                'Cantidad'    => $p['Cantidad'],
                'Costo'       => $producto->Precio_Compra, // 🔹 aquí se copia el precio unitario
            ]);
        }

        return redirect()->route('compras.index')
                        ->with('success', 'Compra registrada correctamente.');
    }


    // Mostrar detalle de compra
    public function show($id)
    {
        $compra = OrdenCompra::with(['proveedor','almacen','detalles.producto'])->findOrFail($id);

        return view('inventarios.compras.show', compact('compra'));
    }

    // Actualizar estado de compra
    public function updateEstado(Request $request, $id)
    {
        $request->validate([
            'Estado' => 'required|string|in:Pendiente,Recibida,Cancelada',
        ]);

        $compra = OrdenCompra::findOrFail($id);
        $compra->Estado = $request->Estado;
        $compra->save();

        return redirect()->route('compras.index')
                        ->with('success', 'Estado actualizado correctamente.');
    }

    public function destroy($id)
    {
        $compra = OrdenCompra::findOrFail($id);

        // 🔹 Si quieres borrar también los detalles asociados
        $compra->detalles()->delete();

        // 🔹 Finalmente borras la compra
        $compra->delete();

        return redirect()->route('compras.index')
                        ->with('success', 'Compra eliminada correctamente.');
    }

}
