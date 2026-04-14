@extends('layouts.app')

@section('title', 'Ver Cliente')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Cliente: {{ $cliente->Nombre }}</h2>

    <div class="grid grid-cols-1 gap-4">
        <div><strong>Documento:</strong> {{ $cliente->Documento }}</div>
        <div><strong>Correo:</strong> {{ $cliente->Correo }}</div>
        <div><strong>Teléfono:</strong> {{ $cliente->Telefono }}</div>
        <div><strong>Creado:</strong> {{ $cliente->created_at?->format('d/m/Y H:i') }}</div>
    </div>

    <div class="mt-6 flex space-x-2">
        <a href="{{ route('clientes.edit', $cliente) }}" class="px-3 py-2 bg-indigo-600 text-white rounded">Editar</a>
        <a href="{{ route('oportunidades.create', ['Id_Cliente' => $cliente->Id_Cliente]) }}" class="px-3 py-2 bg-green-600 text-white rounded">Nueva Oportunidad</a>
        <a href="{{ route('clientes.index') }}" class="px-3 py-2 bg-gray-200 rounded">Volver</a>
    </div>
</div>

<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow mt-6">
    <h3 class="text-lg font-bold mb-3">Oportunidades abiertas</h3>

    @php
        $oportunidades = $cliente->oportunidades()->where('Estado', '<>', 'Cerrado')->orderByDesc('created_at')->get();
    @endphp

    @if($oportunidades->isEmpty())
        <div class="text-gray-600">No hay oportunidades abiertas para este cliente.</div>
    @else
        <table class="w-full mt-2">
            <thead class="bg-gray-50"><tr><th class="p-2 text-left">Título</th><th class="p-2 text-right">Monto</th><th class="p-2">Estado</th><th class="p-2">Cierre</th><th class="p-2">Acciones</th></tr></thead>
            <tbody>
                @foreach($oportunidades as $op)
                <tr class="border-t">
                    <td class="p-2">{{ $op->Titulo }}</td>
                    <td class="p-2 text-right">S/ {{ number_format($op->Monto_Estimado,2) }}</td>
                    <td class="p-2">{{ $op->Estado }}</td>
                    <td class="p-2">{{ $op->Fecha_Cierre?->format('d/m/Y') }}</td>
                    <td class="p-2">
                        <a href="{{ route('oportunidades.edit', $op) }}" class="text-indigo-600 mr-2">Editar</a>

                        <form action="{{ route('oportunidades.cerrar', $op) }}" method="POST" class="inline" onsubmit="return confirm('Cerrar oportunidad?')">
                            @csrf
                            <button type="submit" class="text-green-700 mr-2">Cerrar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
