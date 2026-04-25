@extends('layouts.app')

@section('title', 'Oportunidades')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-6 rounded shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">Oportunidades</h2>
        <div class="flex items-center space-x-2">
            <a href="{{ url()->previous() ?: route('clientes.index') }}" class="px-3 py-2 bg-gray-200 rounded">Volver</a>
            <a href="{{ route('oportunidades.create') }}" class="px-3 py-2 bg-gray-800 text-white rounded">Nueva Oportunidad</a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr><th class="p-3 text-left">Cliente</th><th class="p-3 text-left">Título</th><th class="p-3 text-right">Monto</th><th class="p-3">Estado</th><th class="p-3">Cierre</th></tr></thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach($oportunidades as $op)
                <tr>
                    <td class="p-3">{{ $op->cliente?->Nombre }}</td>
                    <td class="p-3">{{ $op->Titulo }}</td>
                    <td class="p-3 text-right">S/ {{ number_format($op->Monto_Estimado,2) }}</td>
                    <td class="p-3">
                        @php
                            $status = $op->Estado ?? 'N/A';
                            $map = [
                                'Ganada' => 'bg-green-100 text-green-800',
                                'Cerrado' => 'bg-gray-100 text-gray-800',
                                'Prospecto' => 'bg-blue-100 text-blue-800',
                                'Negociación' => 'bg-yellow-100 text-yellow-800',
                            ];
                            $cls = $map[$status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded {{ $cls }}">{{ $status }}</span>
                    </td>
                    <td class="p-3">{{ $op->Fecha_Cierre?->format('d/m/Y') ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $oportunidades->links() }}</div>
</div>
@endsection
