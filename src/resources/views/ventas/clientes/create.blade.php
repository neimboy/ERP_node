@extends('layouts.app')
@section('title', 'Nuevo Cliente')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('clientes.index') }}" class="text-gray-500 hover:text-gray-700">← Volver</a>
        <h1 class="text-2xl font-bold text-gray-800">Nuevo Cliente</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 max-w-xl">
        <form action="{{ route('clientes.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Documento</label>
                <input type="text" name="Documento" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nombre</label>
                <input type="text" name="Nombre" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Correo</label>
                <input type="email" name="Correo" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Teléfono</label>
                <input type="text" name="Telefono" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="pt-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium transition">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection