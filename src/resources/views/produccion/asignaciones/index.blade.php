@extends('layouts.app')
@section('title', 'Asignaciones')

@section('content')
<div class="container">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Asignaciones</h1>
        <a href="{{ route('asignaciones.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Nueva Asignación</a>
    </div>

    @if($asignaciones->isEmpty())
        <p>No hay asignaciones registradas.</p>
    @else
        <table class="min-w-full bg-white border">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-4 py-2 border">Empleado</th>
                    <th class="px-4 py-2 border">Proyecto</th>
                    <th class="px-4 py-2 border">Horas Asignadas</th>
                    <th class="px-4 py-2 border">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($asignaciones as $asignacion)
                <tr class="border">
                    <td class="px-4 py-2">{{ $asignacion->empleado->Nombre ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $asignacion->proyecto->Nombre ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $asignacion->Horas_Asignadas }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('asignaciones.show', $asignacion->Id_Asignacion) }}" class="text-blue-500">Ver</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection