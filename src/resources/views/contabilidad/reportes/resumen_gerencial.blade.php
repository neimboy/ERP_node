@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- ENCABEZADO --}}
    <div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="breadcrumb">Módulo de Contabilidad</span>
            <h2 class="title flex items-center gap-2">
                <span>📋</span> Resumen Gerencial
            </h2>
            <p class="subtitle">Dashboard financiero integrado – KPIs para la toma de decisiones</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="btn btn-secondary btn-sm">
                <i class="fas fa-print mr-1"></i> Imprimir
            </button>
            <a href="{{ route('asientos.index') }}" class="btn btn-ghost btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
        </div>
    </div>

    {{-- KPIs PRINCIPALES --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach($kpis as $kpi)
            @php
                $kpiColor = match($kpi['estado']) {
                    '✅' => 'border-l-emerald-500',
                    '🔴' => 'border-l-red-500',
                    '📈' => 'border-l-blue-500',
                    '📉' => 'border-l-amber-500',
                    '💰' => 'border-l-teal-500',
                    '💸' => 'border-l-rose-500',
                    '🏦' => 'border-l-indigo-500',
                    '🏗️' => 'border-l-sky-500',
                    '📦' => 'border-l-orange-500',
                    '🧾' => 'border-l-purple-500',
                    default => 'border-l-gray-400',
                };
            @endphp
            <div class="kpi-card border-l-4 {{ $kpiColor }} animate-fade-in">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">
                            {{ $kpi['label'] }}
                        </span>
                        <div class="text-xl font-bold text-gray-800 font-mono mt-1.5">
                            {{ $kpi['valor'] }}
                        </div>
                    </div>
                    <span class="text-2xl flex-shrink-0">{{ $kpi['estado'] }}</span>
                </div>
            </div>
        @endforeach
    </div>

    {{-- PANEL DE DATOS CRUZADOS DEL ERP --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        
        {{-- Ventas Cobradas --}}
        <div class="card text-center p-6">
            <div class="flex justify-center mb-3">
                <div class="w-16 h-16 bg-teal-100 rounded-2xl flex items-center justify-center">
                    <span class="text-3xl">💰</span>
                </div>
            </div>
            <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Ventas Cobradas</span>
            <div class="text-2xl font-bold text-teal-600 font-mono mt-2">
                S/. {{ number_format($totalVentas, 2) }}
            </div>
            <p class="text-sm text-gray-400 mt-1">{{ $totalFacturas }} facturas emitidas</p>
            
            {{-- Barra de progreso --}}
            <div class="mt-3 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                <div class="bg-teal-500 h-full rounded-full" style="width: 100%"></div>
            </div>
        </div>

        {{-- Proyectos Activos --}}
        <div class="card text-center p-6">
            <div class="flex justify-center mb-3">
                <div class="w-16 h-16 bg-sky-100 rounded-2xl flex items-center justify-center">
                    <span class="text-3xl">🏗️</span>
                </div>
            </div>
            <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Proyectos Activos</span>
            <div class="text-2xl font-bold text-sky-600 font-mono mt-2">
                {{ $proyectosActivos }}
            </div>
            <p class="text-sm text-gray-400 mt-1">en ejecución actualmente</p>
            
            {{-- Indicador visual --}}
            <div class="mt-3 flex justify-center gap-1">
                @for($i = 0; $i < min($proyectosActivos, 8); $i++)
                    <span class="w-2.5 h-2.5 bg-sky-500 rounded-full"></span>
                @endfor
            </div>
        </div>

        {{-- Valor Inventario --}}
        <div class="card text-center p-6">
            <div class="flex justify-center mb-3">
                <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center">
                    <span class="text-3xl">📦</span>
                </div>
            </div>
            <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Valor Inventario</span>
            <div class="text-2xl font-bold text-orange-600 font-mono mt-2">
                S/. {{ number_format($valorInventario, 2) }}
            </div>
            <p class="text-sm text-gray-400 mt-1">al costo de compra</p>
            
            {{-- Barra de progreso --}}
            <div class="mt-3 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                <div class="bg-orange-500 h-full rounded-full" style="width: {{ min(($valorInventario / ($totalVentas ?: 1)) * 100, 100) }}%"></div>
            </div>
        </div>
    </div>

    {{-- ECUACIÓN CONTABLE --}}
    <div class="card overflow-hidden">
        
        {{-- Cabecera --}}
        <div class="bg-gray-800 text-white px-5 py-4 rounded-t-xl flex items-center gap-3">
            <span class="text-xl">⚖️</span>
            <span class="font-semibold">Ecuación Contable: Activo = Pasivo + Patrimonio</span>
        </div>

        {{-- Cuerpo --}}
        <div class="p-5 sm:p-6">
            
            {{-- Tres columnas --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                
                {{-- Activo --}}
                <div class="bg-emerald-50 rounded-xl p-5 text-center border border-emerald-200">
                    <span class="text-xs text-emerald-600 uppercase tracking-wider font-semibold">Total Activo</span>
                    <div class="text-2xl lg:text-3xl font-bold text-emerald-700 font-mono mt-2">
                        S/. {{ number_format($totalActivos, 2) }}
                    </div>
                </div>

                {{-- Pasivo --}}
                <div class="bg-red-50 rounded-xl p-5 text-center border border-red-200">
                    <span class="text-xs text-red-500 uppercase tracking-wider font-semibold">Total Pasivo</span>
                    <div class="text-2xl lg:text-3xl font-bold text-red-600 font-mono mt-2">
                        S/. {{ number_format($totalPasivos, 2) }}
                    </div>
                </div>

                {{-- Patrimonio --}}
                <div class="bg-indigo-50 rounded-xl p-5 text-center border border-indigo-200">
                    <span class="text-xs text-indigo-500 uppercase tracking-wider font-semibold">Total Patrimonio</span>
                    <div class="text-2xl lg:text-3xl font-bold text-indigo-600 font-mono mt-2">
                        S/. {{ number_format($totalPatrimonio, 2) }}
                    </div>
                </div>
            </div>

            {{-- Verificación de cuadre --}}
            @php $cuadre = abs($totalActivos - ($totalPasivos + $totalPatrimonio)); @endphp
            
            @if($cuadre < 1)
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center justify-center gap-3">
                    <span class="text-2xl">✅</span>
                    <span class="font-semibold text-emerald-700">Balance cuadrado correctamente</span>
                </div>
            @else
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-center justify-center gap-3">
                    <span class="text-2xl">⚠️</span>
                    <span class="font-semibold text-red-600">
                        Diferencia de S/. {{ number_format($cuadre, 2) }} — revisar asientos de regularización
                    </span>
                </div>
            @endif

            {{-- Ecuación visual --}}
            <div class="mt-4 flex flex-col sm:flex-row items-center justify-center gap-2 text-sm text-gray-500 font-mono">
                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded font-bold">Activo</span>
                <span class="text-gray-400 font-bold">=</span>
                <span class="bg-red-100 text-red-600 px-3 py-1 rounded font-bold">Pasivo</span>
                <span class="text-gray-400 font-bold">+</span>
                <span class="bg-indigo-100 text-indigo-600 px-3 py-1 rounded font-bold">Patrimonio</span>
            </div>
        </div>
    </div>

    {{-- RESUMEN RÁPIDO ADICIONAL --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-4">
        <div class="bg-white rounded-xl border border-gray-100 p-3 text-center">
            <span class="text-xs text-gray-400 uppercase tracking-wider">Facturas</span>
            <div class="text-lg font-bold text-gray-700">{{ $totalFacturas }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-3 text-center">
            <span class="text-xs text-gray-400 uppercase tracking-wider">Proyectos</span>
            <div class="text-lg font-bold text-gray-700">{{ $proyectosActivos }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-3 text-center">
            <span class="text-xs text-gray-400 uppercase tracking-wider">Ventas Totales</span>
            <div class="text-lg font-bold text-teal-600 font-mono">S/. {{ number_format($totalVentas, 2) }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-3 text-center">
            <span class="text-xs text-gray-400 uppercase tracking-wider">Inventario</span>
            <div class="text-lg font-bold text-orange-600 font-mono">S/. {{ number_format($valorInventario, 2) }}</div>
        </div>
    </div>

    {{-- NOTA --}}
    <div class="mt-4 text-center text-xs text-gray-400">
        <span>Datos actualizados al {{ date('d/m/Y H:i') }} | Módulo de Contabilidad ERP</span>
    </div>

</div>
@endsection