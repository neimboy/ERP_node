@extends('layouts.app')
@section('title', 'Editar Pago')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-xl">
    <div class="mb-6">
        <a href="{{ route('pagos.index') }}" class="text-gray-500 hover:text-gray-700">← Volver</a>
        <h1 class="text-2xl font-bold mt-2">Editar Pago</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('pagos.update', $pago) }}" method="POST">
            @csrf
            @method('PATCH')

            @include('ventas.pagos._form')

            <div class="mt-4">
                <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection

