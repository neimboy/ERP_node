@extends('layouts.app')
@section('title', 'Pagos')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Pagos</h1>
        <a href="{{ route('pagos.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Nuevo Pago</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Factura</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Cliente</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Monto</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Fecha</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pagos ?? [] as $pago)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $pago->Id ?? $loop->iteration }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $pago->Numero_Factura ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $pago->cliente->Nombre ?? '—' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ number_format($pago->Monto ?? 0, 2) }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ optional($pago->Fecha)->format('Y-m-d') ?? ($pago->created_at?->format('Y-m-d') ?? '-') }}</td>
                    <td class="px-4 py-3 text-center text-sm">
                        <a href="{{ route('pagos.show', $pago->Id ?? $pago) }}" class="text-indigo-600 mr-2">Ver</a>
                        <a href="{{ route('pagos.edit', $pago->Id ?? $pago) }}" class="text-green-600 mr-2">Editar</a>
                        <form action="{{ route('pagos.destroy', $pago->Id ?? $pago) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">No hay pagos registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

