@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Editar Proveedor</h1>

    <form action="{{ route('proveedores.update', $proveedor) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="RUC" class="block text-sm font-medium text-gray-700">RUC</label>
            <input type="text" name="RUC" id="RUC" value="{{ $proveedor->RUC }}" 
                   class="mt-1 block w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
        </div>

        <div>
            <label for="Nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" name="Nombre" id="Nombre" value="{{ $proveedor->Nombre }}" 
                   class="mt-1 block w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
        </div>

        <div>
            <label for="Telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
            <input type="text" name="Telefono" id="Telefono" value="{{ $proveedor->Telefono }}" 
                   class="mt-1 block w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
        </div>

        <div class="flex space-x-3 mt-6">
            <button type="submit" 
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Actualizar
            </button>
            <a href="{{ route('proveedores.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
               ← Volver
            </a>
        </div>
    </form>
</div>
@endsection
