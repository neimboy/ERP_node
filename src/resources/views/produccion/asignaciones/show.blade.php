@extends('layouts.app')
@section('title', 'Detalle de Asignación')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('asignaciones.index') }}" class="text-gray-500 hover:text-gray-700">← Atras</a>
            <h1 class="text-2xl font-bold text-gray-800">Detalle de Asignación</h1>
        </div>
        <a href="{{ route('asignaciones.edit', $asignacion->Id_Asignacion) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Editar</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-500 mb-1">Empleado</p>
                <p class="font-medium text-gray-900">{{ $asignacion->empleado->Nombre_Empleado ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Proyecto</p>
                <p class="font-medium text-gray-900">{{ $asignacion->proyecto->Nombre ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Horas Asignadas</p>
                <p class="font-medium text-gray-900">{{ $asignacion->Horas_Asignadas }} hrs</p>
            </div>
        </div>
    </div>

    <form action="{{ route('asignaciones.destroy', $asignacion->Id_Asignacion) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600 hover:text-red-800 font-medium" onclick="return confirm('¿Estás seguro de eliminar esta asignación?')">Eliminar asignación</button>
    </form>
</div>
@endsection