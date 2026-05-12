@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Proveedores</h1>

    <div class="flex justify-between items-center mb-6">
    <!-- Botón Nuevo Proveedor -->
        <a href="{{ route('proveedores.create') }}" 
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
        + Nuevo Proveedor
        </a>

        <!-- Barra de búsqueda -->
        <form action="{{ route('proveedores.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Buscar proveedor (nombre o RUC)..."
                class="border-gray-300 rounded-lg shadow-sm px-3 py-2">
            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
                Buscar
            </button>
        </form>
    </div>


    <table class="w-full border-collapse border border-gray-200 rounded-lg overflow-hidden">
        <thead class="bg-gray-100 text-sm">
            <tr>
                <th class="border px-4 py-2 text-left">Nombre</th>
                <th class="border px-4 py-2 text-left">RUC</th>
                <th class="border px-4 py-2 text-left">Teléfono</th>
                <th class="border px-4 py-2 text-right">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($proveedores as $proveedor)
                <tr class="hover:bg-gray-50">
                    <td class="border px-4 py-2">{{ $proveedor->Nombre }}</td>
                    <td class="border px-4 py-2">{{ $proveedor->RUC }}</td>
                    <td class="border px-4 py-2">{{ $proveedor->Telefono }}</td>
                    <td class="border px-4 py-2 text-right">
                        <a href="{{ route('proveedores.show', $proveedor) }}" class="text-green-600 hover:underline">Ver</a> |
                        <a href="{{ route('proveedores.edit', $proveedor) }}" class="text-blue-600 hover:underline">Editar</a> |
                        <form action="{{ route('proveedores.destroy', $proveedor) }}" method="POST" class="inline"
                              onsubmit="return confirm('¿Seguro que deseas eliminar este proveedor?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center p-4 text-gray-500">No hay proveedores registrados</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
