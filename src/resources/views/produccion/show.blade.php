@extends('layouts.app')
@section('title', 'Detalle del Proyecto')

@section('content')
<div class="container">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Detalle del Proyecto</h1>
        <div>
            <a href="{{ route('proyectos.edit', $proyecto->Id_Proyecto) }}" class="bg-yellow-500 text-white px-4 py-2 rounded mr-2">Editar</a>
            <a href="{{ route('proyectos.index') }}" class="text-blue-500">Volver</a>
        </div>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <p><strong>Nombre:</strong> {{ $proyecto->Nombre }}</p>
        <p><strong>Cliente:</strong> {{ $proyecto->cliente->Nombre ?? 'N/A' }}</p>
        <p><strong>Fecha Inicio:</strong> {{ $proyecto->Fecha_Inicio }}</p>
        <p><strong>Fecha Fin:</strong> {{ $proyecto->Fecha_Fin }}</p>
        <p><strong>Estado:</strong> {{ $proyecto->Estado }}</p>
    </div>

    <h2 class="text-xl font-bold mt-6 mb-4">Asignaciones</h2>
    @if($proyecto->asignaciones->isEmpty())
        <p>No hay asignaciones.</p>
    @else
        <table class="min-w-full bg-white border mt-4">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-4 py-2 border">Empleado</th>
                    <th class="px-4 py-2 border">Horas Asignadas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proyecto->asignaciones as $asignacion)
                <tr class="border">
                    <td class="px-4 py-2">{{ $asignacion->empleado->Nombre ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $asignacion->Horas_Asignadas }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
