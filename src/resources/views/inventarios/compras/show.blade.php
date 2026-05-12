@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-4 text-gray-800">
        Detalle de Compra #{{ $compra->Id_Orden_Compra }}
    </h1>

    <div class="space-y-2 text-gray-700">
        <p><strong>Proveedor:</strong> {{ $compra->proveedor->Nombre ?? 'Sin proveedor' }}</p>
        <p><strong>Almacén:</strong> {{ $compra->almacen->Nombre ?? 'Sin almacén' }}</p>
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($compra->Fecha)->format('d/m/Y') }}</p>
        <p><strong>Estado actual:</strong> 
            <span class="px-2 py-1 rounded text-white 
                @if($compra->Estado === 'Pendiente') bg-yellow-500 
                @elseif($compra->Estado === 'Recibida') bg-green-600 
                @else bg-red-600 @endif">
                {{ $compra->Estado }}
            </span>
        </p>
    </div>

    <!-- Formulario inline para actualizar estado -->
    <form action="{{ route('compras.updateEstado', $compra->Id_Orden_Compra) }}" method="POST" class="mt-4">
        @csrf
        @method('PATCH')
        <label for="Estado" class="block text-gray-700 font-semibold mb-2">Actualizar Estado</label>
        <select name="Estado" onchange="this.form.submit()" 
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            <option value="Pendiente" {{ $compra->Estado === 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="Recibida" {{ $compra->Estado === 'Recibida' ? 'selected' : '' }}>Recibida</option>
            <option value="Cancelada" {{ $compra->Estado === 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
        </select>
    </form>

    <h3 class="text-xl font-semibold mt-6 mb-3 text-gray-800">Productos</h3>
    <table class="w-full border-collapse border border-gray-200 rounded-lg overflow-hidden">
        <thead class="bg-gray-100 text-sm">
            <tr>
                <th class="border px-4 py-2 text-left">Producto</th>
                <th class="border px-4 py-2 text-left">Cantidad</th>
                <th class="border px-4 py-2 text-left">Precio Unitario</th>
                <th class="border px-4 py-2 text-left">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($compra->detalles as $detalle)
                <tr class="hover:bg-gray-50">
                    <td class="border px-4 py-2">{{ $detalle->producto->Nombre }}</td>
                    <td class="border px-4 py-2">{{ $detalle->Cantidad }}</td>
                    <td class="border px-4 py-2">{{ number_format($detalle->Costo, 2) }}</td>
                    <td class="border px-4 py-2">{{ number_format($detalle->subtotal, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center p-4 text-gray-500">No hay productos registrados en esta compra</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Total calculado desde el accesor del modelo -->
    <h4 class="text-lg font-bold mt-4 text-gray-800">
        Total: {{ number_format($compra->total, 2) }}
    </h4>

    <div class="flex space-x-3 mt-6">
        <a href="{{ route('compras.index') }}" 
           class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
           ← Volver
        </a>
        <form action="{{ route('compras.destroy', $compra->Id_Orden_Compra) }}" method="POST" 
              onsubmit="return confirm('¿Seguro que deseas eliminar esta compra?');">
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
