@extends('layouts.app')
@section('title', 'Nuevo Proyecto')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Nuevo Proyecto</h1>
    <a href="{{ route('proyectos.index') }}" class="text-blue-500 mb-4 inline-block">Volver</a>

    <form action="{{ route('proyectos.store') }}" method="POST" class="max-w-lg">
        @csrf
        
        <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Nombre del Proyecto</label>
            <input type="text" name="Nombre" class="w-full border px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Cliente</label>
            <select name="Id_Cliente" class="w-full border px-3 py-2" required>
                <option value="">Seleccione un Cliente</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->Id_Cliente }}">{{ $cliente->Nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Fecha de Inicio</label>
            <input type="date" name="Fecha_Inicio" class="w-full border px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Fecha Fin</label>
            <input type="date" name="Fecha_Fin" class="w-full border px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Estado</label>
            <select name="Estado" class="w-full border px-3 py-2">
                <option value="Pendiente">Pendiente</option>
                <option value="En Progreso">En Progreso</option>
                <option value="Completado">Completado</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar</button>
    </form>
</div>
@endsection
