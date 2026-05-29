@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Compras</h1>

    <div class="flex justify-between items-center mb-6">
    <!-- Botón Nueva Compra -->
        <a href="{{ route('compras.create') }}" 
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
        + Nueva Compra
        </a>

        <!-- Barra de búsqueda -->
        <form action="{{ route('compras.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Buscar compra (proveedor o fecha)..."
                class="border-gray-300 rounded-lg shadow-sm px-3 py-2">
            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
                Buscar
            </button>
        </form>
    </div>


    <table class="w-full border-collapse border border-gray-200 rounded-lg overflow-hidden">
        <thead class="bg-gray-100 text-sm">
            <tr>
                <th class="border px-4 py-2 text-left">N° Orden</th>
                <th class="border px-4 py-2 text-left">Proveedor</th>
                <th class="border px-4 py-2 text-left">Almacén</th>
                <th class="border px-4 py-2 text-left">Fecha</th>
                <th class="border px-4 py-2 text-left">Estado</th>
                <th class="border px-4 py-2 text-left">Total</th>
                <th class="border px-4 py-2 text-right">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($compras as $compra)
                <tr class="hover:bg-gray-50">
                    <td class="border px-4 py-2">{{ $compra->Id_Orden_Compra }}</td>
                    <td class="border px-4 py-2">{{ $compra->proveedor->Nombre ?? 'Sin proveedor' }}</td>
                    <td class="border px-4 py-2">{{ $compra->almacen->Nombre ?? 'Sin almacén' }}</td>
                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($compra->Fecha)->format('d/m/Y') }}</td>
                    <td class="border px-4 py-2">
                        <span class="px-2 py-1 rounded text-white 
                            @if($compra->Estado === 'Pendiente') bg-yellow-500 
                            @elseif($compra->Estado === 'Recibida') bg-green-600 
                            @else bg-red-600 @endif">
                            {{ $compra->Estado }}
                        </span>
                    </td>
                    <td class="border px-4 py-2">
                        {{ number_format($compra->total, 2) }}
                    </td>

                    <td class="border px-4 py-2 text-right space-x-2">
                        <a href="{{ route('compras.show', $compra->Id_Orden_Compra) }}" 
                           class="text-green-600 hover:underline">Ver</a>
                        
                        <!-- Formulario inline para actualizar estado -->
                        <form action="{{ route('compras.updateEstado', $compra->Id_Orden_Compra) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <select name="Estado" onchange="this.form.submit()" 
                                class="text-sm rounded px-2 py-1
                                    @if($compra->Estado === 'Pendiente') bg-yellow-500 text-white
                                    @elseif($compra->Estado === 'Recibida') bg-green-600 text-white
                                    @elseif($compra->Estado === 'Cancelada') bg-red-600 text-white
                                    @endif">
                                <option value="Pendiente" {{ $compra->Estado === 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="Recibida" {{ $compra->Estado === 'Recibida' ? 'selected' : '' }}>Recibida</option>
                                <option value="Cancelada" {{ $compra->Estado === 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                        </form>


                        <form action="{{ route('compras.destroy', $compra->Id_Orden_Compra) }}" 
                              method="POST" class="inline"
                              onsubmit="return confirm('¿Seguro que deseas eliminar esta compra?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center p-4 text-gray-500">No hay compras registradas</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
