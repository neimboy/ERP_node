@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-4 text-gray-800">Nueva Categoría</h1>

    <form action="{{ route('categorias.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block font-semibold text-gray-700">Nombre</label>
            <input type="text" name="Nombre" 
                   class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
        </div>

        <div class="flex space-x-3">
            <button type="submit" 
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Guardar
            </button>
            <a href="{{ route('categorias.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
                ← Volver
            </a>
        </div>
    </form>
</div>
@endsection
