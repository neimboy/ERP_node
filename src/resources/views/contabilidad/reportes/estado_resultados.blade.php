@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- ENCABEZADO --}}
    <div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="breadcrumb">Estados Financieros</span>
            <h2 class="title flex items-center gap-2">
                <span>📈</span> Estado de Resultados
            </h2>
            <p class="subtitle">Ganancias y Pérdidas acumuladas del período | Soles (S/.)</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="btn btn-secondary btn-sm">
                <i class="fas fa-print mr-1"></i> Imprimir
            </button>
            <a href="{{ route('contabilidad.estado_resultados_semestral') }}" class="btn btn-ghost btn-sm text-indigo-600">
                <i class="fas fa-chart-bar mr-1"></i> Ver Semestral
            </a>
            <a href="{{ route('asientos.index') }}" class="btn btn-ghost btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
        </div>
    </div>

    {{-- REPORTE PRINCIPAL --}}
    <div class="card overflow-hidden animate-fade-in">
        
        {{-- CABECERA DEL REPORTE --}}
        <div class="bg-gray-800 text-white px-6 py-5 rounded-t-xl">
            <div class="text-center">
                <h3 class="text-lg font-bold uppercase tracking-wider">Estado de Resultados</h3>
                <p class="text-gray-400 text-sm mt-1">Del período contable seleccionado</p>
                <p class="text-gray-500 text-xs mt-0.5">Expresado en Soles (S/.)</p>
            </div>
        </div>

        {{-- SECCIÓN: INGRESOS --}}
        <div class="border-b border-gray-200">
            <div class="bg-teal-600 text-white px-5 py-3 flex items-center gap-3">
                <span class="text-lg">💰</span>
                <span class="font-semibold uppercase tracking-wider text-sm">Ingresos</span>
            </div>

            <div class="px-5 py-4">
                @forelse($detalleIngresos as $ing)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-xs text-teal-600 font-bold bg-teal-50 px-2 py-0.5 rounded">
                                {{ $ing['codigo'] }}
                            </span>
                            <span class="text-gray-700 text-sm">{{ $ing['nombre'] }}</span>
                        </div>
                        <span class="font-mono text-sm font-medium text-teal-700">
                            S/. {{ number_format($ing['monto'], 2) }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-400">
                        <span class="text-2xl block mb-1">📋</span>
                        <p class="text-sm">Sin ingresos registrados en este período.</p>
                    </div>
                @endforelse
            </div>

            {{-- Total Ingresos --}}
            <div class="bg-teal-50 px-5 py-3 flex justify-between items-center border-t-2 border-teal-200">
                <span class="text-xs font-bold text-teal-700 uppercase tracking-wider">Total Ingresos</span>
                <span class="font-mono text-lg font-bold text-teal-700">
                    S/. {{ number_format($ingresos, 2) }}
                </span>
            </div>
        </div>

        {{-- SECCIÓN: COSTOS Y GASTOS --}}
        <div class="border-b border-gray-200">
            <div class="bg-red-500 text-white px-5 py-3 flex items-center gap-3">
                <span class="text-lg">💸</span>
                <span class="font-semibold uppercase tracking-wider text-sm">Costos y Gastos</span>
            </div>

            <div class="px-5 py-4">
                @forelse($detalleGastos as $gas)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-xs text-red-500 font-bold bg-red-50 px-2 py-0.5 rounded">
                                {{ $gas['codigo'] }}
                            </span>
                            <span class="text-gray-700 text-sm">{{ $gas['nombre'] }}</span>
                        </div>
                        <span class="font-mono text-sm font-medium text-red-500">
                            (S/. {{ number_format($gas['monto'], 2) }})
                        </span>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-400">
                        <span class="text-2xl block mb-1">📋</span>
                        <p class="text-sm">Sin gastos registrados en este período.</p>
                    </div>
                @endforelse
            </div>

            {{-- Total Gastos --}}
            <div class="bg-red-50 px-5 py-3 flex justify-between items-center border-t-2 border-red-200">
                <span class="text-xs font-bold text-red-600 uppercase tracking-wider">Total Costos y Gastos</span>
                <span class="font-mono text-lg font-bold text-red-600">
                    (S/. {{ number_format($gastos, 2) }})
                </span>
            </div>
        </div>

        {{-- SECCIÓN: RESULTADO NETO --}}
        <div class="{{ $utilidadNeta >= 0 ? 'bg-emerald-600' : 'bg-red-600' }} text-white px-6 py-5">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">{{ $utilidadNeta >= 0 ? '✅' : '🔴' }}</span>
                    <div>
                        <span class="text-lg font-bold uppercase tracking-wider">
                            {{ $utilidadNeta >= 0 ? 'Utilidad Neta' : 'Pérdida Neta' }}
                        </span>
                        <p class="text-white/70 text-xs mt-0.5">
                            {{ $utilidadNeta >= 0 ? 'Resultado positivo del ejercicio' : 'Resultado negativo del ejercicio' }}
                        </p>
                    </div>
                </div>
                <span class="font-mono text-2xl font-extrabold tracking-tight">
                    S/. {{ number_format(abs($utilidadNeta), 2) }}
                </span>
            </div>
        </div>

        {{-- MARGEN NETO --}}
        @if($ingresos > 0)
            <div class="bg-gray-50 px-5 py-3 flex justify-between items-center border-t border-gray-200">
                <span class="text-sm text-gray-500 flex items-center gap-1.5">
                    <i class="fas fa-percentage text-gray-400"></i> Margen Neto
                </span>
                <span class="font-mono text-sm font-bold {{ $utilidadNeta >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                    {{ number_format($utilidadNeta / $ingresos * 100, 1) }}%
                </span>
            </div>
        @endif
    </div>

    {{-- RESUMEN VISUAL --}}
    <div class="grid grid-cols-3 gap-4 mt-6">
        <div class="kpi-card kpi-ingresos text-center">
            <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Ingresos</span>
            <div class="text-lg font-bold text-teal-600 font-mono mt-1">
                S/. {{ number_format($ingresos, 2) }}
            </div>
        </div>
        <div class="kpi-card kpi-gastos text-center">
            <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Gastos</span>
            <div class="text-lg font-bold text-red-500 font-mono mt-1">
                S/. {{ number_format($gastos, 2) }}
            </div>
        </div>
        <div class="kpi-card {{ $utilidadNeta >= 0 ? 'kpi-ingresos' : 'kpi-gastos' }} text-center">
            <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Resultado</span>
            <div class="text-lg font-bold {{ $utilidadNeta >= 0 ? 'text-emerald-600' : 'text-red-500' }} font-mono mt-1">
                {{ $utilidadNeta >= 0 ? '' : '(S/. ' . number_format(abs($utilidadNeta), 2) . ')' }}
                {{ $utilidadNeta >= 0 ? 'S/. ' . number_format($utilidadNeta, 2) : '' }}
            </div>
        </div>
    </div>

    {{-- NOTA ACLARATORIA --}}
    <div class="mt-4 bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm text-gray-500">
        <div class="flex items-start gap-2">
            <i class="fas fa-info-circle text-gray-400 mt-0.5"></i>
            <div>
                <p class="font-medium text-gray-600 mb-1">Fórmula del Estado de Resultados:</p>
                <p class="font-mono text-xs">
                    <span class="text-teal-600">Ingresos</span> 
                    − 
                    <span class="text-red-500">Costos y Gastos</span> 
                    = 
                    <span class="{{ $utilidadNeta >= 0 ? 'text-emerald-600' : 'text-red-500' }} font-bold">
                        {{ $utilidadNeta >= 0 ? 'Utilidad Neta' : 'Pérdida Neta' }}
                    </span>
                </p>
            </div>
        </div>
    </div>

</div>
@endsection