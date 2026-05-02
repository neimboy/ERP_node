@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-6">Detalle del Almacén</h1>

    <p><strong>ID:</strong> {{ $almacen->Id_Almacen }}</p>
    <p><strong>Nombre:</strong> {{ $almacen->Nombre }}</p>
    <p><strong>Dirección:</strong> {{ $almacen->Direccion }}</p>

    <a href="{{ route('almacenes.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded mt-4 inline-block">
        ← Volver
    </a>
</div>
@endsection
