@extends('layouts.app')

@section('title', 'Oportunidad')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">Oportunidad: {{ $oportunidad->Titulo }}</h2>
        <div class="flex items-center space-x-2">
            <a href="{{ route('oportunidades.index') }}" class="px-3 py-2 bg-gray-200 rounded">Volver</a>
            <a href="{{ route('oportunidades.edit', ['oportunidad' => $oportunidad->Id_Oportunidad]) }}" class="px-3 py-2 bg-yellow-600 text-white rounded">Editar</a>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <p class="text-sm text-gray-600">Cliente</p>
            <p class="font-medium">{{ $oportunidad->cliente?->Nombre ?? '—' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Estado</p>
            <p class="font-medium">{{ $oportunidad->Estado }}</p>
        </div>
        <div class="col-span-2">
            <p class="text-sm text-gray-600">Descripción</p>
            <p class="mt-1">{{ $oportunidad->Descripcion ?? '—' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Monto Estimado</p>
            <p class="font-medium">S/ {{ number_format($oportunidad->Monto_Estimado ?? 0, 2) }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Fecha Cierre</p>
            <p class="font-medium">{{ $oportunidad->Fecha_Cierre?->format('d/m/Y') ?? '—' }}</p>
        </div>
    </div>

    <div class="mt-6">
        @if($oportunidad->Id_Orden)
            <a href="{{ route('ordenes.show', $oportunidad->Id_Orden) }}" class="px-3 py-2 bg-blue-600 text-white rounded">Ver Orden asociada</a>
        @endif

        <a href="{{ route('cotizaciones.create', ['oportunidad_id' => $oportunidad->Id_Oportunidad, 'Id_Cliente' => $oportunidad->Id_Cliente]) }}" class="px-3 py-2 bg-green-600 text-white rounded ml-2">Crear Cotización</a>
    </div>
</div>
@endsection
