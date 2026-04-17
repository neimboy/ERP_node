<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Facturas</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded">
                <table class="w-full">
                    <thead class="bg-gray-50"><tr><th class="p-2 text-left">#</th><th class="p-2 text-left">Fecha</th><th class="p-2 text-left">Cliente</th><th class="p-2 text-right">Total</th><th class="p-2">Acciones</th></tr></thead>
                    <tbody>
                        @foreach($facturas as $f)
                        <tr class="border-t">
                            <td class="p-2">{{ $f->Id_Factura }}</td>
                            <td class="p-2">{{ $f->Fecha }}</td>
                            <td class="p-2">{{ $f->orden->cliente->Nombre ?? '—' }}</td>
                            <td class="p-2 text-right">S/ {{ number_format($f->Total,2) }}</td>
                            <td class="p-2">
                                <a href="{{ route('facturas.show', $f) }}" class="text-blue-600 mr-2">Ver / Imprimir</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $facturas->links() }}</div>
        </div>
    </div>
</x-app-layout>
