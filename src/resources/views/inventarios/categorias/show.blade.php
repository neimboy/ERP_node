@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-4 text-gray-800">Detalle de Categoría</h1>

    <div class="space-y-2 text-gray-700">
        <p><strong>ID:</strong> {{ $categoria->Id_Categoria }}</p>
        <p><strong>Nombre:</strong> {{ $categoria->Nombre }}</p>
        <p><strong>Creado:</strong> {{ $categoria->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Actualizado:</strong> {{ $categoria->updated_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="flex space-x-3 mt-6">
        <a href="{{ route('categorias.index') }}" 
           class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
           ← Volver
        </a>
        <a href="{{ route('categorias.edit', $categoria) }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
           Editar
        </a>
        <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" 
              onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?');">
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
