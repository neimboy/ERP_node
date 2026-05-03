@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-4 text-gray-800">Crear Producto</h1>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('productos.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block font-semibold text-gray-700">Código</label>
            <input type="text" name="Codigo" 
                   class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
        </div>

        <div>
            <label class="block font-semibold text-gray-700">Nombre</label>
            <input type="text" name="Nombre" 
                   class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
        </div>

        <div>
            <label class="block font-semibold text-gray-700">Precio Compra</label>
            <input type="number" step="0.01" name="Precio_Compra" 
                   class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
        </div>

        <div>
            <label class="block font-semibold text-gray-700">Precio Venta</label>
            <input type="number" step="0.01" name="Precio_Venta" 
                   class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300">
        </div>

        <div>
            <label class="block font-semibold text-gray-700">Proveedor</label>
            <select name="Id_Proveedor" 
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
                @foreach($proveedores as $proveedor)
                    <option value="{{ $proveedor->Id_Proveedor }}">{{ $proveedor->Nombre }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-semibold text-gray-700">Categoría</label>
            <select name="Id_Categoria" 
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->Id_Categoria }}">{{ $categoria->Nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex space-x-3">
            <button type="submit" 
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Guardar
            </button>
            <a href="{{ route('productos.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
                ← Volver
            </a>
        </div>
    </form>
</div>
@endsection
