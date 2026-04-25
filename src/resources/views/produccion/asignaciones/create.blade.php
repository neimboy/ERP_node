@extends('layouts.app')
@section('title', 'Nueva Asignación')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ $proyecto_seleccionado ? route('proyectos.show', $proyecto_seleccionado) : route('asignaciones.index') }}" class="text-gray-500 hover:text-gray-700">← Volver</a>
        <h1 class="text-2xl font-bold text-gray-800">Nueva Asignación</h1>
    </div>

    @if($empleados->isEmpty())
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-yellow-800">No hay empleados disponibles para asignar. Todos los empleados ya están asignados a este proyecto o no hay empleados registrados.</p>
            <a href="{{ $proyecto_seleccionado ? route('proyectos.show', $proyecto_seleccionado) : route('asignaciones.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm mt-2 inline-block">Volver al proyecto</a>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 max-w-xl">
            <form action="{{ route('asignaciones.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Empleado</label>
                    <select name="Id_Empleado" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">Seleccione un Empleado</option>
                        @foreach($empleados as $empleado)
                            <option value="{{ $empleado->Id_Empleado }}">{{ $empleado->Nombre_Empleado }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Proyecto</label>
                    <select name="Id_Proyecto" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" {{ $proyecto_seleccionado ? 'disabled' : '' }} required>
                        <option value="">Seleccione un Proyecto</option>
                        @foreach($proyectos as $proyecto)
                            <option value="{{ $proyecto->Id_Proyecto }}" {{ $proyecto_seleccionado == $proyecto->Id_Proyecto ? 'selected' : '' }}>
                                {{ $proyecto->Nombre }}
                            </option>
                        @endforeach
                    </select>
                    @if($proyecto_seleccionado)
                        <input type="hidden" name="Id_Proyecto" value="{{ $proyecto_seleccionado }}">
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Horas Asignadas</label>
                    <input type="number" name="Horas_Asignadas" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" min="1" required>
                </div>

                <div class="pt-2">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium transition">Guardar</button>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection