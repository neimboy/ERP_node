@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Registrar Nuevo Almacén</h1>

    <form action="{{ route('almacenes.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="Nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" name="Nombre" id="Nombre" maxlength="150"
                   class="mt-1 block w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
        </div>

        <div>
            <label for="Direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
            <textarea name="Direccion" id="Direccion" maxlength="255" rows="3"
                      class="mt-1 block w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"></textarea>
        </div>

        <div class="flex space-x-3 mt-6">
            <button type="submit" 
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Guardar
            </button>
            <a href="{{ route('almacenes.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
               ← Volver
            </a>
        </div>
    </form>
</div>
@endsection

