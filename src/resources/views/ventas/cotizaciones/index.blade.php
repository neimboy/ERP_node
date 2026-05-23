<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Cotizaciones</h2>
            <a href="{{ route('cotizaciones.create') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-medium">+ Nueva Cotización</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg overflow-x-auto">
                <table class="w-full min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 text-left">#</th>
                            <th class="p-3 text-left">Cliente</th>
                            <th class="p-3 text-left">Fecha</th>
                            <th class="p-3 text-left">Vencimiento</th>
                            <th class="p-3 text-right">Total</th>
                            <th class="p-3 text-left">Estado</th>
                            <th class="p-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($cotizaciones as $cot)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 font-semibold">{{ $cot->Id_Cotizacion }}</td>
                            <td class="p-3">{{ $cot->cliente->Nombre ?? 'N/A' }}</td>
                            <td class="p-3">{{ $cot->Fecha->format('d/m/Y') }}</td>
                            <td class="p-3">{{ $cot->Fecha_Vencimiento->format('d/m/Y') }}</td>
                            <td class="p-3 text-right font-semibold">S/ {{ number_format($cot->Total, 2) }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 text-xs font-medium rounded
                                    {{ $cot->Estado === 'PENDIENTE' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $cot->Estado === 'ACEPTADA' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $cot->Estado === 'RECHAZADA' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $cot->Estado === 'CONVERTIDA' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $cot->Estado === 'VENCIDA' ? 'bg-gray-100 text-gray-800' : '' }}
                                ">
                                    {{ $cot->Estado }}
                                </span>
                            </td>
                            <td class="p-3">
                                <a href="{{ route('cotizaciones.show', $cot) }}" class="text-blue-600 hover:text-blue-800 mr-3">Ver</a>
                                <button onclick="eliminarConFetch({{ $cot->Id_Cotizacion }})" class="text-red-600 hover:text-red-900" style="background:none; border:none; cursor:pointer; padding:0;">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $cotizaciones->links() }}</div>
        </div>
    </div>

    <script>
    function eliminarConFetch(cotizacionId) {
        if (confirm('¿Estás seguro de que quieres eliminar esta cotización?')) {
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
                        window.location.reload();
                    }, 500);
                } else {
                    alert('Error: ' + response.status);
                }
            })
            .catch(error => {
                alert('Error: ' + error);
            });
        }
    }
    </script>
</x-app-layout>
