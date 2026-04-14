@extends('layouts.app')

@section('title', 'Oportunidades')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-6 rounded shadow">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">Oportunidades</h2>
        <a href="{{ route('oportunidades.create') }}" class="px-3 py-2 bg-gray-800 text-white rounded">Nueva Oportunidad</a>
    </div>

    <table class="w-full">
        <thead class="bg-gray-50"><tr><th class="p-2 text-left">Cliente</th><th class="p-2 text-left">Título</th><th class="p-2 text-right">Monto</th><th class="p-2">Estado</th><th class="p-2">Cierre</th></tr></thead>
        <tbody>
            @foreach($oportunidades as $op)
            <tr class="border-t">
                <td class="p-2">{{ $op->cliente?->Nombre }}</td>
                <td class="p-2">{{ $op->Titulo }}</td>
                <td class="p-2 text-right">S/ {{ number_format($op->Monto_Estimado,2) }}</td>
                <td class="p-2">{{ $op->Estado }}</td>
                <td class="p-2">{{ $op->Fecha_Cierre?->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">{{ $oportunidades->links() }}</div>
</div>
@endsection
