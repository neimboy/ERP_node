<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Generar Factura</h2>
            <a href="{{ route('facturas.index') }}" class="px-4 py-2 bg-gray-200 rounded">Volver</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 bg-white p-6 rounded shadow">
            <form action="{{ route('facturas.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Orden de Venta</label>
                    <select name="Id_Orden" id="Id_Orden" class="mt-1 block w-full border-gray-300 rounded">
                        <option value="">-- Seleccionar orden --</option>
                        @foreach($ordenes as $o)
                            <option value="{{ $o->Id_Orden }}" {{ (string)($ordenId ?? '') === (string)$o->Id_Orden ? 'selected' : '' }}>
                                #{{ $o->Id_Orden }} - {{ $o->cliente->Nombre ?? 'Cliente' }} - {{ $o->Fecha }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center space-x-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Generar Factura</button>
                    <a href="{{ route('ordenes.index') }}" class="px-4 py-2 bg-gray-200 rounded">Ir a Órdenes</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
