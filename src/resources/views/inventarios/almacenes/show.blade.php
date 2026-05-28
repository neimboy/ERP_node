@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Detalle del Almacén</h1>

    <div class="space-y-4 text-gray-700">
        <div class="flex justify-between border-b pb-2">
            <span class="font-semibold">Código interno:</span>
            <span>{{ $almacen->Id_Almacen }}</span>
        </div>
        <div class="flex justify-between border-b pb-2">
            <span class="font-semibold">Nombre:</span>
            <span>{{ $almacen->Nombre }}</span>
        </div>
        <div class="flex justify-between border-b pb-2">
            <span class="font-semibold">Dirección:</span>
            <span>{{ $almacen->Direccion }}</span>
        </div>
        <div class="flex justify-between border-b pb-2">
            <span class="font-semibold">Creado:</span>
            <span>{{ $almacen->created_at ? $almacen->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
        </div>
        <div class="flex justify-between border-b pb-2">
            <span class="font-semibold">Actualizado:</span>
            <span>{{ $almacen->updated_at ? $almacen->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
        </div>
    </div>

    <!-- Productos en el almacén -->
    <h3 class="text-xl font-semibold mt-6 mb-3 text-gray-800">Productos en este almacén</h3>
    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead>
            <tr>
                <th class="border px-4 py-2">Código</th>
                <th class="border px-4 py-2">Nombre</th>
                <th class="border px-4 py-2">Cantidad</th>
                <th class="border px-4 py-2">Costo</th>
                <th class="border px-4 py-2">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($almacen->productos as $detalle)
                <tr>
                    <td class="border px-4 py-2">{{ $detalle->producto->Codigo }}</td>
                    <td class="border px-4 py-2">{{ $detalle->producto->Nombre }}</td>
                    <td class="border px-4 py-2">
                        {{ $detalle->producto->stockEnAlmacen($almacen->Id_Almacen) }}
                    </td>
                    <td class="border px-4 py-2">{{ number_format($detalle->Costo, 2) }}</td>
                    <td class="border px-4 py-2">{{ number_format($detalle->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-6 flex space-x-3">
        <a href="{{ route('almacenes.index') }}" 
           class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
            ← Volver
        </a>
        <a href="{{ route('almacenes.edit', $almacen) }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Editar
        </a>
        <form action="{{ route('almacenes.destroy', $almacen) }}" method="POST" 
              onsubmit="return confirm('¿Seguro que deseas eliminar este almacén?');">
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
