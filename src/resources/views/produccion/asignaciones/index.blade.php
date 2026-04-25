@extends('layouts.app')
@section('title', 'Asignaciones')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Asignaciones</h1>
        <a href="{{ route('asignaciones.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium transition">+ Nueva</a>
    </div>

    @if($asignaciones->isEmpty())
        <div class="bg-gray-50 rounded-lg p-8 text-center">
            <p class="text-gray-500">No hay asignaciones registradas.</p>
        </div>
    @else
        <div class="grid gap-3">
            @foreach($asignaciones as $asignacion)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4 flex-1">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="text-indigo-600 font-semibold">{{ substr($asignacion->empleado->Nombre ?? 'N/A', 0, 1) }}</span>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $asignacion->empleado->Nombre ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500">{{ $asignacion->proyecto->Nombre ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-indigo-600 font-semibold">{{ $asignacion->Horas_Asignadas }}</p>
                            <p class="text-xs text-gray-500">horas</p>
                        </div>
                        <a href="{{ route('asignaciones.show', $asignacion->Id_Asignacion) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Ver</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection