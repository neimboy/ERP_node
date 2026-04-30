@extends('layouts.app')
@section('title', 'Proyectos')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Proyectos</h1>
        <a href="{{ route('produccion.proyectos.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium transition">+ Nuevo</a>
    </div>

    @if($proyectos->isEmpty())
        <div class="bg-gray-50 rounded-lg p-8 text-center">
            <p class="text-gray-500">No hay proyectos registrados.</p>
        </div>
    @else
        <div class="grid gap-3">
            @foreach($proyectos as $proyecto)
            <div class="bg-white rounded-xl shadow-sm border-2 border-gray-100 hover:border-indigo-200 p-5 hover:shadow-md transition cursor-pointer proyecto-card" data-proyecto-id="{{ $proyecto->Id_Proyecto }}">
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
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<div id="proyectoDrawer" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 transition-opacity" id="drawerOverlay"></div>
    <div class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-xl transform transition-transform duration-300 flex flex-col" id="drawerPanel">
        <div class="flex items-center justify-between p-5 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">Detalle del Proyecto</h2>
            <button type="button" id="closeDrawer" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-5" id="drawerContent">
            <div class="text-center text-gray-500">Cargando...</div>
        </div>
    </div>
</div>

<script>
    window.proyectosData = @json($proyectos);
</script>
<script src="{{ asset('js/proyecto-drawer.js') }}"></script>
@endsection
