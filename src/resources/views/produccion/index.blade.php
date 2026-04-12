@extends('layouts.app')
@section('title', 'Proyectos')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Proyectos</h1>
        <a href="{{ route('proyectos.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium transition">+ Nuevo</a>
    </div>

    @if($proyectos->isEmpty())
        <div class="bg-gray-50 rounded-lg p-8 text-center">
            <p class="text-gray-500">No hay proyectos registrados.</p>
        </div>
    @else
        <div class="grid gap-4">
            @foreach($proyectos as $proyecto)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-xl font-semibold text-gray-900">{{ $proyecto->Nombre }}</h3>
                            @php
                                $estadoColors = [
                                    'Pendiente' => 'bg-yellow-100 text-yellow-800',
                                    'En Progreso' => 'bg-blue-100 text-blue-800',
                                    'Completado' => 'bg-green-100 text-green-800'
                                ];
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $estadoColors[$proyecto->Estado] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $proyecto->Estado }}
                            </span>
                        </div>
                        <p class="text-gray-600 mb-1">{{ $proyecto->cliente->Nombre ?? 'Sin cliente' }}</p>
                        <div class="flex gap-4 text-sm text-gray-500">
                            <span>Inicio: {{ $proyecto->Fecha_Inicio ?? 'N/A' }}</span>
                            <span>Fin: {{ $proyecto->Fecha_Fin ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <a href="{{ route('proyectos.show', $proyecto->Id_Proyecto) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Ver</a>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
