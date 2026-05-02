@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-6">Editar Almacén</h1>

    <form action="{{ route('almacenes.update', $almacen) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="Nombre" class="block font-semibold">Nombre</label>
            <input type="text" name="Nombre" value="{{ $almacen->Nombre }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label for="Direccion" class="block font-semibold">Dirección</label>
            <textarea name="Direccion" class="w-full border rounded px-3 py-2">{{ $almacen->Direccion }}</textarea>
        </div>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Actualizar</button>
    </form>
</div>
@endsection
