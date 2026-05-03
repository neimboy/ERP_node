@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-4 text-gray-800">Detalle del Producto</h1>

    <div class="space-y-2 text-gray-700">
        <p><strong>Código:</strong> {{ $producto->Codigo }}</p>
        <p><strong>Nombre:</strong> {{ $producto->Nombre }}</p>
        <p><strong>Precio Compra:</strong> {{ number_format($producto->Precio_Compra, 2) }}</p>
        <p><strong>Precio Venta:</strong> {{ number_format($producto->Precio_Venta, 2) }}</p>
        <p><strong>Proveedor:</strong> {{ $producto->proveedor->Nombre ?? 'Sin proveedor' }}</p>
        <p><strong>Categoría:</strong> {{ $producto->categoria->Nombre ?? 'Sin categoría' }}</p>
        <p><strong>Creado:</strong> {{ $producto->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Actualizado:</strong> {{ $producto->updated_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="flex space-x-3 mt-6">
        <a href="{{ route('productos.index') }}" 
           class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
           ← Volver
        </a>
        <a href="{{ route('productos.edit', $producto) }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
           Editar
        </a>
        <form action="{{ route('productos.destroy', $producto) }}" method="POST" 
              onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
                Eliminar
            </button>
        </form>
    </div>
</div>
@endsection
