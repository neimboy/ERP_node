@extends('layouts.app')

@section('content')

<div class="mb-6">
    <a href="{{ route('cotizaciones.index') }}" class="text-indigo-600 hover:text-indigo-800">
        <i class="fas fa-arrow-left mr-2"></i> Volver
    </a>
    <h2 class="text-3xl font-bold text-gray-800 mt-2">Cotización #{{ $cotizacion->Id_Cotizacion }}</h2>
</div>

{{-- Mensajes flash gestionados desde la plantilla principal para evitar duplicados --}}

<!-- Encabezado -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Info General -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Información</h3>
        <div class="space-y-3">
            <div>
                <p class="text-sm text-gray-600">Fecha</p>
                <p class="font-medium">{{ optional($cotizacion->Fecha)->format('d/m/Y') ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Vencimiento</p>
                <p class="font-medium">{{ optional($cotizacion->Fecha_Vencimiento)->format('d/m/Y') ?? 'N/A' }}</p>
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
            @php $estado = strtoupper($cotizacion->Estado ?? $cotizacion->estado ?? ''); @endphp
            <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                {{ $estado === 'BORRADOR' ? 'bg-gray-100 text-gray-800' : '' }}
                {{ $estado === 'ENVIADA' ? 'bg-yellow-100 text-yellow-800' : '' }}
                {{ $estado === 'ACEPTADA' ? 'bg-green-100 text-green-800' : '' }}
                {{ $estado === 'RECHAZADA' ? 'bg-red-100 text-red-800' : '' }}
            ">
                {{ $estado }}
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

    @php
        $costosDirectos = $cotizacion->detalles->sum(function($d) {
            if (method_exists($d, 'calcularTotal')) return $d->calcularTotal();
            return $d->Total ?? ($d->Cantidad * ($d->Precio_Unitario ?? 0));
        });
        $gastosGenerales = round($costosDirectos * 0.06, 2);
        $utilidad = round($costosDirectos * 0.10, 2);
        $subtotalCalc = round($costosDirectos + $gastosGenerales + $utilidad, 2);
        $impuestoCalc = round($subtotalCalc * 0.18, 2);
        $presupuestoTotal = round($subtotalCalc + $impuestoCalc, 2);
    @endphp

    <div class="flex justify-end mt-6 pt-4 border-t">
        <div class="text-right w-80">
            <div class="flex justify-between text-sm text-gray-600 mb-1">
                <div>Costos directos</div>
                <div>S/ {{ number_format($costosDirectos, 2) }}</div>
            </div>
            <div class="flex justify-between text-sm text-gray-600 mb-1">
                <div>Gastos generales (6%)</div>
                <div>S/ {{ number_format($gastosGenerales, 2) }}</div>
            </div>
            <div class="flex justify-between text-sm text-gray-600 mb-1">
                <div>Utilidad (10%)</div>
                <div>S/ {{ number_format($utilidad, 2) }}</div>
            </div>
            <div class="flex justify-between font-semibold text-gray-800 mt-2">
                <div>Subtotal</div>
                <div>S/ {{ number_format($subtotalCalc, 2) }}</div>
            </div>
            <div class="flex justify-between text-sm text-gray-600 mt-1">
                <div>IGV (18%)</div>
                <div>S/ {{ number_format($impuestoCalc, 2) }}</div>
            </div>
            <div class="flex justify-between text-2xl font-bold mt-3">
                <div>Presupuesto Total</div>
                <div>S/ {{ number_format($presupuestoTotal, 2) }}</div>
            </div>
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
        @if($estado !== 'ACEPTADA')
        <form action="{{ route('cotizaciones.aceptar', $cotizacion) }}" method="POST" onsubmit="return confirm('Marcar cotización como ACEPTADA?')">
            @csrf
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Aceptar</button>
        </form>

        <!-- Botón: Aceptar y generar orden en un solo paso (mostrar como 'Orden') -->
        <form action="{{ route('cotizaciones.aceptar', $cotizacion) }}" method="POST" onsubmit="return confirm('Aceptar esta cotización y generar la orden ahora?')">
            @csrf
            <input type="hidden" name="generar_orden" value="1">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Orden</button>
        </form>
        @endif
        @if($estado === 'ACEPTADA')
        <form action="{{ route('cotizaciones.generarOrden', $cotizacion) }}" method="POST" onsubmit="return confirm('¿Generar orden a partir de esta cotización?')">
            @csrf
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Orden</button>
        </form>
        @endif
    </div>
</div>

<script>
// Evitar doble envío: al enviar cualquier formulario deshabilitar el botón submit
document.querySelectorAll('form').forEach(f => {
    f.addEventListener('submit', function() {
        const btn = f.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.textContent = 'Procesando...';
        }
    });
});

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
