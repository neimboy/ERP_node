@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-6">Crear Almacén</h1>

    <form action="{{ route('almacenes.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="Nombre" class="block font-semibold">Nombre</label>
            <input type="text" name="Nombre" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label for="Direccion" class="block font-semibold">Dirección</label>
            <textarea name="Direccion" class="w-full border rounded px-3 py-2"></textarea>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
    </form>
</div>
@endsection
