@extends('layouts.app')
@section('title', 'Editar Asignación')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('proyectos.show', $asignacion->Id_Proyecto) }}" class="text-gray-500 hover:text-gray-700">← Volver</a>
        <h1 class="text-2xl font-bold text-gray-800">Editar Asignación</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 max-w-xl">
        <form action="{{ route('produccion.asignaciones.update', $asignacion->Id_Asignacion) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Empleado</label>
                <select name="Id_Empleado" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                    @foreach($empleados as $empleado)
                        <option value="{{ $empleado->Id_Empleado }}" {{ $empleado->Id_Empleado == $asignacion->Id_Empleado ? 'selected' : '' }}>
                            {{ $empleado->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Proyecto</label>
                <select name="Id_Proyecto" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                    @foreach($proyectos as $proyecto)
                        <option value="{{ $proyecto->Id_Proyecto }}" {{ $proyecto->Id_Proyecto == $asignacion->Id_Proyecto ? 'selected' : '' }}>
                            {{ $proyecto->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Horas Asignadas</label>
                <input type="number" name="Horas_Asignadas" value="{{ $asignacion->Horas_Asignadas }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" min="1" required>
            </div>

            <div class="pt-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium transition">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection
