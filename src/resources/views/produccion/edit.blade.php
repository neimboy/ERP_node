@extends('layouts.app')
@section('title', 'Editar Proyecto')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Editar Proyecto</h1>
    <a href="{{ route('proyectos.show', $proyecto->Id_Proyecto) }}" class="text-blue-500 mb-4 inline-block">Volver</a>

    <form action="{{ route('proyectos.update', $proyecto->Id_Proyecto) }}" method="POST" class="max-w-lg">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Nombre del Proyecto</label>
            <input type="text" name="Nombre" value="{{ $proyecto->Nombre }}" class="w-full border px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Cliente</label>
            <select name="Id_Cliente" class="w-full border px-3 py-2" required>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->Id_Cliente }}" {{ $cliente->Id_Cliente == $proyecto->Id_Cliente ? 'selected' : '' }}>
                        {{ $cliente->Nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Fecha de Inicio</label>
            <input type="date" name="Fecha_Inicio" value="{{ $proyecto->Fecha_Inicio }}" class="w-full border px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Fecha Fin</label>
            <input type="date" name="Fecha_Fin" value="{{ $proyecto->Fecha_Fin }}" class="w-full border px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Estado</label>
            <select name="Estado" class="w-full border px-3 py-2">
                <option value="Pendiente" {{ $proyecto->Estado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="En Progreso" {{ $proyecto->Estado == 'En Progreso' ? 'selected' : '' }}>En Progreso</option>
                <option value="Completado" {{ $proyecto->Estado == 'Completado' ? 'selected' : '' }}>Completado</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Actualizar</button>
    </form>

    <form action="{{ route('proyectos.destroy', $proyecto->Id_Proyecto) }}" method="POST" class="mt-4">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded" onclick="return confirm('¿Estás seguro de eliminar este proyecto?')">Eliminar</button>
    </form>
</div>
@endsection