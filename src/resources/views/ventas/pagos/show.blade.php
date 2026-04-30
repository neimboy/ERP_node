@extends('layouts.app')
@section('title', 'Detalle de Pago')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('pagos.index') }}" class="text-gray-500 hover:text-gray-700">← Volver</a>
        <h1 class="text-2xl font-bold mt-2">Detalle de Pago</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <dl class="grid grid-cols-1 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">ID</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $pago->Id ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Número de Factura</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $pago->Numero_Factura ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Cliente</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $pago->cliente->Nombre ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Monto</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ number_format($pago->Monto ?? 0, 2) }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Fecha</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ optional($pago->Fecha)->format('Y-m-d') ?? ($pago->created_at?->format('Y-m-d') ?? '-') }}</dd>
            </div>
        </dl>
    </div>
</div>
@endsection

