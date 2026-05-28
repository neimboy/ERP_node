@extends('layouts.app')
@section('title', 'Nuevo Proyecto - Tipo')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('proyectos.index') }}" class="text-gray-500 hover:text-gray-700">← Volver</a>
        <h1 class="text-2xl font-bold text-gray-800">Seleccionar Tipo de Proyecto</h1>
    </div>

    <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto mt-8">
        <a href="{{ route('proyectos.create-produccion') }}"
           class="group bg-white rounded-2xl shadow-sm border-2 border-gray-100 hover:border-indigo-400 p-8 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
            <div class="flex flex-col items-center text-center gap-5">
                <div class="w-20 h-20 rounded-2xl bg-indigo-100 flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                    <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Proyecto de Producción</h2>
                    <p class="text-gray-500 leading-relaxed">
                        Crea un proyecto que consume productos del inventario. Ideal para fabricación, ensamblaje o manufactura.
                    </p>
                </div>
                <span class="inline-flex items-center gap-2 text-indigo-600 font-medium group-hover:gap-3 transition-all">
                    Crear proyecto
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </span>
            </div>
        </a>

        <a href="{{ route('proyectos.create-servicio') }}"
           class="group bg-white rounded-2xl shadow-sm border-2 border-gray-100 hover:border-emerald-400 p-8 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
            <div class="flex flex-col items-center text-center gap-5">
                <div class="w-20 h-20 rounded-2xl bg-emerald-100 flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                    <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Servicio</h2>
                    <p class="text-gray-500 leading-relaxed">
                        Crea un proyecto de servicio con gastos asociados. Ideal para consultoría, mantenimiento o soporte.
                    </p>
                </div>
                <span class="inline-flex items-center gap-2 text-emerald-600 font-medium group-hover:gap-3 transition-all">
                    Crear servicio
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </span>
            </div>
        </a>
    </div>
</div>
@endsection
