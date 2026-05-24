<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Órdenes</h2>
            <a href="{{ route('ordenes.create') }}" class="px-4 py-2 bg-gray-800 text-white rounded">Nueva Orden</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg overflow-x-auto">
                <table class="w-full min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50"><tr><th class="p-3 text-left">#</th><th class="p-3 text-left">Cliente</th><th class="p-3 text-left">Fecha</th><th class="p-3 text-right">Total</th><th class="p-3 text-left">Estado</th><th class="p-3">Acciones</th></tr></thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($ordenes as $orden)
                        <tr>
                            <td class="p-3">{{ $orden->Id_Orden }}</td>
                            <td class="p-3">{{ $orden->cliente->Nombre ?? 'N/A' }}</td>
                            <td class="p-3">{{ $orden->Fecha }}</td>
                            <td class="p-3 text-right">S/ {{ number_format($orden->Total ?? $orden->total ?? $orden->detalles->sum(fn($d) => ($d->Precio ?? 0) * ($d->Cantidad ?? 0)), 2) }}</td>
                            <td class="p-3">
                                @php
                                    $status = $orden->Estado ?? 'N/A';
                                    $map = [
                                        'Pagada' => 'bg-green-100 text-green-800',
                                        'Pagado' => 'bg-green-100 text-green-800',
                                        'Pendiente' => 'bg-yellow-100 text-yellow-800',
                                        'Pendiente_Pago' => 'bg-yellow-100 text-yellow-800',
                                        'PendientePago' => 'bg-yellow-100 text-yellow-800',
                                        'Facturada' => 'bg-blue-100 text-blue-800',
                                    ];
                                    $cls = $map[$status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded {{ $cls }}">{{ $status }}</span>
                            </td>
                            <td class="p-3">
                                <a href="{{ route('ordenes.show', $orden) }}" class="text-blue-600">Ver</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $ordenes->links() }}</div>
        </div>
    </div>
</x-app-layout>
