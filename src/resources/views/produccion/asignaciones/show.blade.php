@extends('layouts.app')
@section('title', 'Detalle de Asignación')

@section('content')
<div class="container">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Detalle de Asignación</h1>
        <div>
            <a href="{{ route('asignaciones.edit', $asignacion->Id_Asignacion) }}" class="bg-yellow-500 text-white px-4 py-2 rounded mr-2">Editar</a>
            <a href="{{ route('asignaciones.index') }}" class="text-blue-500">Volver</a>
        </div>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <p><strong>Empleado:</strong> {{ $asignacion->empleado->Nombre ?? 'N/A' }}</p>
        <p><strong>Proyecto:</strong> {{ $asignacion->proyecto->Nombre ?? 'N/A' }}</p>
        <p><strong>Horas Asignadas:</strong> {{ $asignacion->Horas_Asignadas }}</p>
    </div>

    <form action="{{ route('asignaciones.destroy', $asignacion->Id_Asignacion) }}" method="POST" class="mt-4">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded" onclick="return confirm('¿Estás seguro de eliminar esta asignación?')">Eliminar</button>
    </form>
</div>
@endsection