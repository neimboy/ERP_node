@extends('layouts.app')

@section('content')

<div class="mb-6">
    <a href="{{ route('cotizaciones.index') }}" class="text-indigo-600 hover:text-indigo-800">
        <i class="fas fa-arrow-left mr-2"></i> Volver
    </a>
    <h2 class="text-3xl font-bold text-gray-800 mt-2">Cotización #{{ $cotizacion->Id_Cotizacion }}</h2>
</div>

@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
    </div>
@endif

<!-- Encabezado -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Info General -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Información</h3>
        <div class="space-y-3">
            <div>
                <p class="text-sm text-gray-600">Fecha</p>
                <p class="font-medium">{{ $cotizacion->Fecha->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Vencimiento</p>
                <p class="font-medium">{{ $cotizacion->Fecha_Vencimiento->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Cliente -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Cliente</h3>
        <div class="space-y-2">
            <p class="font-medium text-gray-800">{{ $cotizacion->cliente->Nombre ?? 'N/A' }}</p>
            <p class="text-sm text-gray-600">{{ $cotizacion->cliente->Correo ?? '' }}</p>
            <p class="text-sm text-gray-600">{{ $cotizacion->cliente->Telefono ?? '' }}</p>
        </div>
    </div>

    <!-- Estado -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Estado</h3>
        <div class="space-y-2">
            <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                {{ $cotizacion->Estado === 'Pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                {{ $cotizacion->Estado === 'Aceptada' ? 'bg-green-100 text-green-800' : '' }}
                {{ $cotizacion->Estado === 'Rechazada' ? 'bg-red-100 text-red-800' : '' }}
                {{ $cotizacion->Estado === 'Convertida' ? 'bg-blue-100 text-blue-800' : '' }}
            ">
                {{ $cotizacion->Estado }}
            </span>
        </div>
    </div>
</div>

<!-- Productos -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Productos</h3>
    
    @if($cotizacion->detalles->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Producto</th>
                    <th class="px-4 py-3 text-right text-sm font-semibold">Cantidad</th>
                    <th class="px-4 py-3 text-right text-sm font-semibold">Precio</th>
                    <th class="px-4 py-3 text-right text-sm font-semibold">Descuento</th>
                    <th class="px-4 py-3 text-right text-sm font-semibold">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cotizacion->detalles as $detalle)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm">{{ $detalle->producto->Nombre ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-sm text-right">{{ $detalle->Cantidad }}</td>
                    <td class="px-4 py-3 text-sm text-right">S/ {{ number_format($detalle->Precio_Unitario, 2) }}</td>
                    <td class="px-4 py-3 text-sm text-right">{{ $detalle->Descuento }}%</td>
                    <td class="px-4 py-3 text-sm text-right font-medium">S/ {{ number_format($detalle->Total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex justify-end mt-6 pt-4 border-t">
        <div class="text-right">
            <p class="text-gray-600 mb-2">Total:</p>
            <p class="text-2xl font-bold text-gray-800">S/ {{ number_format($cotizacion->Total, 2) }}</p>
        </div>
    </div>
    @else
    <p class="text-center text-gray-500 py-8">No hay productos</p>
    @endif
</div>

<!-- Acciones -->
<div class="bg-white rounded-lg shadow p-6 space-y-3">
    <div class="flex gap-3">
        <a href="{{ route('cotizaciones.edit', $cotizacion) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
            Editar
        </a>
        <button onclick="eliminarConFetch({{ $cotizacion->Id_Cotizacion }})" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
            Eliminar
        </button>
        @if($cotizacion->Estado === 'Pendiente')
        <a href="#" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
            Convertir a Orden
        </a>
        @endif
    </div>
</div>

<script>
function eliminarConFetch(cotizacionId) {
    if (confirm('¿Eliminar esta cotización?')) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/ventas/cotizaciones/${cotizacionId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.ok || response.status === 302) {
                setTimeout(() => {
                    window.location.href = '/ventas/cotizaciones';
                }, 500);
            }
        })
        .catch(error => alert('Error: ' + error));
    }
}
</script>

@endsection
