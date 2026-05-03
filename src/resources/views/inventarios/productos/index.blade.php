@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Productos</h1>

    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('productos.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
           + Nuevo Producto
        </a>
        
    </div>

    <table class="w-full border-collapse border border-gray-200 rounded-lg overflow-hidden">
        <thead class="bg-gray-100 text-sm">
            <tr>
                <th class="border px-4 py-2 text-left">Código</th>
                <th class="border px-4 py-2 text-left">Nombre</th>
                <th class="border px-4 py-2 text-left">Precio Compra</th>
                <th class="border px-4 py-2 text-left">Precio Venta</th>
                <th class="border px-4 py-2 text-left">Proveedor</th>
                <th class="border px-4 py-2 text-left">Categoría</th>
                <th class="border px-4 py-2 text-right">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productos as $producto)
                <tr class="hover:bg-gray-50">
                    <td class="border px-4 py-2">{{ $producto->Codigo }}</td>
                    <td class="border px-4 py-2">{{ $producto->Nombre }}</td>
                    <td class="border px-4 py-2">{{ number_format($producto->Precio_Compra, 2) }}</td>
                    <td class="border px-4 py-2">{{ number_format($producto->Precio_Venta, 2) }}</td>
                    <td class="border px-4 py-2">{{ $producto->proveedor->Nombre ?? 'Sin proveedor' }}</td>
                    <td class="border px-4 py-2">{{ $producto->categoria->Nombre ?? 'Sin categoría' }}</td>
                    <td class="border px-4 py-2 text-right">
                        <a href="{{ route('productos.show', $producto) }}" class="text-green-600 hover:underline">Ver</a> |
                        <a href="{{ route('productos.edit', $producto) }}" class="text-blue-600 hover:underline">Editar</a> |
                        <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="inline"
                              onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center p-4 text-gray-500">No hay productos registrados</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
