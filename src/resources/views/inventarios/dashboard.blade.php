@extends('layouts.app')

@section('title', 'Dashboard Inventarios')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

            <h2 class="text-2xl font-bold mb-4">Dashboard Inventarios</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-purple-100 rounded-lg p-4 shadow">
                    <h3 class="text-sm text-gray-600">Productos</h3>
                    <p class="text-2xl font-bold">{{ $productosCount }}</p>
                    <a href="{{ route('productos.index') }}" class="text-blue-600 text-sm hover:underline">Ver productos</a>
                </div>

                <div class="bg-blue-100 rounded-lg p-4 shadow">
                    <h3 class="text-sm text-gray-600">Almacenes</h3>
                    <p class="text-2xl font-bold">{{ $almacenesCount }}</p>
                    <a href="{{ route('almacenes.index') }}" class="text-blue-600 text-sm hover:underline">Ver almacenes</a>
                </div>

                <div class="bg-green-100 rounded-lg p-4 shadow">
                    <h3 class="text-sm text-gray-600">Stock total</h3>
                    <p class="text-2xl font-bold">{{ $stockTotal }}</p>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-lg font-bold mb-2">Últimos movimientos</h3>
                <ul class="list-disc pl-6">
                    @foreach($movimientos as $mov)
                        <li>{{ $mov->tipo }} - {{ $mov->cantidad }} ({{ $mov->created_at->format('d/m/Y') }})</li>
                    @endforeach
                </ul>
            </div>

            <div class="mt-6">
                <h3 class="text-lg font-bold mb-2">Últimas compras</h3>
                <ul class="list-disc pl-6">
                    @foreach($compras as $compra)
                        <li>{{ $compra->proveedor->Nombre ?? 'Proveedor' }} - S/ {{ $compra->total }} ({{ $compra->created_at->format('d/m/Y') }})</li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection
