@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Categorías</h1>

    <div class="flex justify-between items-center mb-6">
    <!-- Botón Nueva Categoría -->
        <a href="{{ route('categorias.create') }}" 
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
        + Nueva Categoría
        </a>

        <!-- Barra de búsqueda -->
        <form action="{{ route('categorias.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Buscar categoría..."
                class="border-gray-300 rounded-lg shadow-sm px-3 py-2">
            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
                Buscar
            </button>
        </form>
    </div>

    <table class="w-full border-collapse border border-gray-200 rounded-lg overflow-hidden">
        <thead class="bg-gray-100 text-sm">
            <tr>
                <th class="border px-4 py-2 text-left">ID</th>
                <th class="border px-4 py-2 text-left">Nombre</th>
                <th class="border px-4 py-2 text-left">Creado</th>
                <th class="border px-4 py-2 text-right">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categorias as $categoria)
                <tr class="hover:bg-gray-50">
                    <td class="border px-4 py-2">{{ $categoria->Id_Categoria }}</td>
                    <td class="border px-4 py-2">{{ $categoria->Nombre }}</td>
                    <td class="border px-4 py-2">{{ $categoria->created_at->format('d/m/Y H:i') }}</td>
                    <td class="border px-4 py-2 text-right">
                        <a href="{{ route('categorias.show', $categoria) }}" class="text-green-600 hover:underline">Ver</a> |
                        <a href="{{ route('categorias.edit', $categoria) }}" class="text-blue-600 hover:underline">Editar</a> |
                        <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" class="inline"
                              onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center p-4 text-gray-500">No hay categorías registradas</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
