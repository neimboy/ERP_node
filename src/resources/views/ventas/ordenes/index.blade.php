<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Órdenes</h2>
            <a href="{{ route('ordenes.create') }}" class="px-4 py-2 bg-gray-800 text-white rounded">Nueva Orden</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded">
                <table class="w-full">
                    <thead class="bg-gray-50"><tr><th class="p-2 text-left">#</th><th class="p-2 text-left">Cliente</th><th class="p-2 text-left">Fecha</th><th class="p-2 text-right">Total</th><th class="p-2 text-left">Estado</th><th class="p-2">Acciones</th></tr></thead>
                    <tbody>
                        @foreach($ordenes as $orden)
                        <tr class="border-t">
                            <td class="p-2">{{ $orden->Id_Orden }}</td>
                            <td class="p-2">{{ $orden->cliente->Nombre ?? 'N/A' }}</td>
                            <td class="p-2">{{ $orden->Fecha }}</td>
                            <td class="p-2 text-right">S/ {{ number_format($orden->detalles->sum(fn($d) => $d->Precio * $d->Cantidad), 2) }}</td>
                            <td class="p-2">{{ $orden->Estado }}</td>
                            <td class="p-2">
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
