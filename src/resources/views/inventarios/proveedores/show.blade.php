@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Detalle del Proveedor</h1>

    <div class="space-y-2 text-gray-700">
        <p><strong>RUC:</strong> {{ $proveedor->RUC }}</p>
        <p><strong>Nombre:</strong> {{ $proveedor->Nombre }}</p>
        <p><strong>Teléfono:</strong> {{ $proveedor->Telefono }}</p>
        <p><strong>Creado:</strong> {{ $proveedor->created_at ? $proveedor->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
        <p><strong>Actualizado:</strong> {{ $proveedor->updated_at ? $proveedor->updated_at->format('d/m/Y H:i') : 'N/A' }}</p>
    </div>

    <div class="flex space-x-3 mt-6">
        <a href="{{ route('proveedores.index') }}" 
           class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
           ← Volver
        </a>
        <a href="{{ route('proveedores.edit', $proveedor) }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
           Editar
        </a>
        <form action="{{ route('proveedores.destroy', $proveedor) }}" method="POST" 
              onsubmit="return confirm('¿Seguro que deseas eliminar este proveedor?');">
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
