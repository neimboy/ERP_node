@extends('layouts.app')
@section('title', 'Editar Asignación')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Editar Asignación</h1>
    <a href="{{ route('asignaciones.show', $asignacion->Id_Asignacion) }}" class="text-blue-500 mb-4 inline-block">Volver</a>

    <form action="{{ route('asignaciones.update', $asignacion->Id_Asignacion) }}" method="POST" class="max-w-lg">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Empleado</label>
            <select name="Id_Empleado" class="w-full border px-3 py-2" required>
                @foreach($empleados as $empleado)
                    <option value="{{ $empleado->Id_Empleado }}" {{ $empleado->Id_Empleado == $asignacion->Id_Empleado ? 'selected' : '' }}>
                        {{ $empleado->Nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Proyecto</label>
            <select name="Id_Proyecto" class="w-full border px-3 py-2" required>
                @foreach($proyectos as $proyecto)
                    <option value="{{ $proyecto->Id_Proyecto }}" {{ $proyecto->Id_Proyecto == $asignacion->Id_Proyecto ? 'selected' : '' }}>
                        {{ $proyecto->Nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Horas Asignadas</label>
            <input type="number" name="Horas_Asignadas" value="{{ $asignacion->Horas_Asignadas }}" class="w-full border px-3 py-2" min="1" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Actualizar</button>
    </form>
</div>
@endsection