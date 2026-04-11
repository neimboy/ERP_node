@extends('layouts.app')
@section('title', 'Nueva Asignación')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Nueva Asignación</h1>
    <a href="{{ route('asignaciones.index') }}" class="text-blue-500 mb-4 inline-block">Volver</a>

    <form action="{{ route('asignaciones.store') }}" method="POST" class="max-w-lg">
        @csrf
        
        <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Empleado</label>
            <select name="Id_Empleado" class="w-full border px-3 py-2" required>
                <option value="">Seleccione un Empleado</option>
                @foreach($empleados as $empleado)
                    <option value="{{ $empleado->Id_Empleado }}">{{ $empleado->Nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Proyecto</label>
            <select name="Id_Proyecto" class="w-full border px-3 py-2" required>
                <option value="">Seleccione un Proyecto</option>
                @foreach($proyectos as $proyecto)
                    <option value="{{ $proyecto->Id_Proyecto }}">{{ $proyecto->Nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Horas Asignadas</label>
            <input type="number" name="Horas_Asignadas" class="w-full border px-3 py-2" min="1" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar</button>
    </form>
</div>
@endsection