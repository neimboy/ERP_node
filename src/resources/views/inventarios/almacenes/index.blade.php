@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Almacenes</h1>

    <div class="flex justify-between items-center mb-6">
        <!-- Botón Nuevo Almacén -->
        <a href="{{ route('almacenes.create') }}" 
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
        + Nuevo Almacén
        </a>

        <!-- Barra de búsqueda -->
        <form action="{{ route('almacenes.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Buscar almacén..."
                class="border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
            <button type="submit" 
                    class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
                Buscar
            </button>
        </form>
    </div>


    <table class="w-full border-collapse border border-gray-200 rounded-lg overflow-hidden">
        <thead class="bg-gray-100 text-sm">
            <tr>
                <th class="border px-4 py-2 text-left">Nombre</th>
                <th class="border px-4 py-2 text-left">Dirección</th>
                <th class="border px-4 py-2 text-right">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($almacenes as $almacen)
                <tr class="hover:bg-gray-50">
                    <td class="border px-4 py-2">{{ $almacen->Nombre }}</td>
                    <td class="border px-4 py-2">{{ $almacen->Direccion }}</td>
                    <td class="border px-4 py-2 text-right">
                        <a href="{{ route('almacenes.show', $almacen) }}" class="text-green-600 hover:underline">Ver</a> |
                        <a href="{{ route('almacenes.edit', $almacen) }}" class="text-blue-600 hover:underline">Editar</a> |
                        <form action="{{ route('almacenes.destroy', $almacen) }}" method="POST" class="inline"
                              onsubmit="return confirm('¿Seguro que deseas eliminar este almacén?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center p-4 text-gray-500">No hay almacenes registrados</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
