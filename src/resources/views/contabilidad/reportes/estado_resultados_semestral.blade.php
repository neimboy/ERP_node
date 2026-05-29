@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- ENCABEZADO --}}
    <div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="breadcrumb">Estados Financieros</span>
            <h2 class="title flex items-center gap-2">
                <span>📊</span> Estado de Resultados Semestral
            </h2>
            <p class="subtitle">Evolución mensual de ingresos, costos y utilidad | Soles (S/.)</p>
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

    {{-- ESTADO VACÍO --}}
    @if(empty($meses))
        <div class="text-center py-16">
            <span class="text-6xl mb-4 block">📭</span>
            <h3 class="text-lg font-semibold text-gray-600 mb-2">No hay períodos con movimientos registrados</h3>
            <p class="text-gray-400">Registra asientos contables para visualizar la evolución mensual.</p>
        </div>
    @else

    {{-- TARJETAS KPI --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="kpi-card kpi-ingresos">
            <div class="kpi-icon">💰</div>
            <span class="kpi-label">Total Ingresos</span>
            <div class="kpi-value text-teal-600">S/. {{ number_format($totalIngresos, 2) }}</div>
        </div>
        <div class="kpi-card kpi-gastos">
            <div class="kpi-icon">💸</div>
            <span class="kpi-label">Costos + Gastos</span>
            <div class="kpi-value text-red-500">S/. {{ number_format($totalCostos + $totalGastos, 2) }}</div>
        </div>
        <div class="kpi-card kpi-default">
            <div class="kpi-icon">📈</div>
            <span class="kpi-label">Utilidad Operativa</span>
            <div class="kpi-value text-blue-600">S/. {{ number_format($totalUtilidadOperativa, 2) }}</div>
        </div>
        <div class="kpi-card kpi-default">
            <div class="kpi-icon">🎯</div>
            <span class="kpi-label">Margen Operativo</span>
            <div class="kpi-value text-indigo-600">{{ $margenOperativoTotal }}%</div>
        </div>
    </div>

    {{-- TABLA COMPARATIVA MENSUAL --}}
    <div class="card overflow-hidden">
        
        {{-- Cabecera de la tabla --}}
        <div class="bg-gray-800 text-white px-5 py-4 rounded-t-xl flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-xl">📅</span>
                <span class="font-semibold">Evolución Mensual</span>
            </div>
            <span class="badge bg-white/20 text-white text-xs">
                {{ count($meses) }} meses
            </span>
        </div>

        {{-- Tabla --}}
        <div class="overflow-x-auto">
            <table class="table min-w-[1000px]">
                <thead>
                    <tr>
                        <th class="pl-5 text-left w-36">Período</th>
                        <th class="text-right w-32">Ingresos</th>
                        <th class="text-right w-32">Costos</th>
                        <th class="text-right w-32">Gastos Oper.</th>
                        <th class="text-right w-36">Util. Bruta</th>
                        <th class="text-center w-28">Margen Bruto</th>
                        <th class="text-right w-36">Util. Operativa</th>
                        <th class="text-center pr-5 w-28">Margen Oper.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($meses as $fila)
                        @php
                            $margenBrutoClase = match(true) {
                                $fila['margen_bruto'] >= 40 => 'margen-excelente',
                                $fila['margen_bruto'] >= 20 => 'margen-bueno',
                                default => 'margen-malo',
                            };
                            $margenOperClase = match(true) {
                                $fila['margen_operativo'] >= 15 => 'margen-excelente',
                                $fila['margen_operativo'] >= 5 => 'margen-bueno',
                                default => 'margen-malo',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                            {{-- Período --}}
                            <td class="pl-5">
                                <span class="font-semibold text-gray-700">{{ $fila['label'] }}</span>
                            </td>

                            {{-- Ingresos --}}
                            <td class="text-right">
                                @if($fila['ingresos'] > 0)
                                    <span class="font-mono text-sm font-medium text-emerald-600">
                                        S/. {{ number_format($fila['ingresos'], 2) }}
                                    </span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>

                            {{-- Costos --}}
                            <td class="text-right">
                                @if($fila['costos'] > 0)
                                    <span class="font-mono text-sm text-red-500">
                                        (S/. {{ number_format($fila['costos'], 2) }})
                                    </span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>

                            {{-- Gastos Oper. --}}
                            <td class="text-right">
                                @if($fila['gastos'] > 0)
                                    <span class="font-mono text-sm text-amber-600">
                                        (S/. {{ number_format($fila['gastos'], 2) }})
                                    </span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>

                            {{-- Utilidad Bruta --}}
                            <td class="text-right">
                                <span class="font-mono text-sm font-bold {{ $fila['utilidad_bruta'] >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                    {{ $fila['utilidad_bruta'] >= 0 ? '' : '(S/. ' . number_format(abs($fila['utilidad_bruta']), 2) . ')' }}
                                    {{ $fila['utilidad_bruta'] >= 0 ? 'S/. ' . number_format($fila['utilidad_bruta'], 2) : '' }}
                                </span>
                            </td>

                            {{-- Margen Bruto --}}
                            <td class="text-center">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ $margenBrutoClase }}">
                                    {{ $fila['margen_bruto'] }}%
                                </span>
                            </td>

                            {{-- Utilidad Operativa --}}
                            <td class="text-right">
                                <span class="font-mono text-sm font-bold {{ $fila['utilidad_operativa'] >= 0 ? 'text-blue-600' : 'text-red-500' }}">
                                    {{ $fila['utilidad_operativa'] >= 0 ? 'S/. ' . number_format($fila['utilidad_operativa'], 2) : '(S/. ' . number_format(abs($fila['utilidad_operativa']), 2) . ')' }}
                                </span>
                            </td>

                            {{-- Margen Operativo --}}
                            <td class="text-center pr-5">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ $margenOperClase }}">
                                    {{ $fila['margen_operativo'] }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                {{-- PIE: TOTALES --}}
                <tfoot>
                    <tr class="bg-gray-800 text-white">
                        <td class="pl-5 py-3 font-bold text-sm uppercase tracking-wider">
                            Total Período
                        </td>
                        <td class="text-right font-mono font-bold text-emerald-400">
                            S/. {{ number_format($totalIngresos, 2) }}
                        </td>
                        <td class="text-right font-mono font-bold text-red-400">
                            (S/. {{ number_format($totalCostos, 2) }})
                        </td>
                        <td class="text-right font-mono font-bold text-amber-400">
                            (S/. {{ number_format($totalGastos, 2) }})
                        </td>
                        <td class="text-right font-mono font-bold text-emerald-400">
                            S/. {{ number_format($totalUtilidadBruta, 2) }}
                        </td>
                        <td class="text-center">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-500 text-white">
                                {{ $margenBrutoTotal }}%
                            </span>
                        </td>
                        <td class="text-right font-mono font-bold text-blue-400">
                            S/. {{ number_format($totalUtilidadOperativa, 2) }}
                        </td>
                        <td class="text-center pr-5">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-blue-500 text-white">
                                {{ $margenOperativoTotal }}%
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- GRÁFICO DE BARRAS SIMPLE (MÁRGENES) --}}
    <div class="card mt-6 p-5">
        <h3 class="section-title text-base mb-4">
            <span class="w-1 h-5 bg-indigo-500 rounded-full mr-2.5"></span>
            Visualización de Márgenes por Mes
        </h3>
        <div class="space-y-3">
            @foreach($meses as $fila)
                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-500 w-20 text-right font-medium">{{ $fila['label'] }}</span>
                    
                    {{-- Barra de Margen Bruto --}}
                    <div class="flex-1 flex items-center gap-2">
                        <span class="text-xs text-gray-400 w-8">Bruto</span>
                        <div class="flex-1 bg-gray-100 rounded-full h-5 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 {{ $fila['margen_bruto'] >= 40 ? 'bg-emerald-500' : ($fila['margen_bruto'] >= 20 ? 'bg-amber-500' : 'bg-red-500') }}"
                                 style="width: {{ min($fila['margen_bruto'], 100) }}%">
                            </div>
                        </div>
                        <span class="text-xs font-bold w-10 {{ $fila['margen_bruto'] >= 40 ? 'text-emerald-600' : ($fila['margen_bruto'] >= 20 ? 'text-amber-600' : 'text-red-500') }}">
                            {{ $fila['margen_bruto'] }}%
                        </span>
                    </div>
                    
                    {{-- Barra de Margen Operativo --}}
                    <div class="flex-1 flex items-center gap-2">
                        <span class="text-xs text-gray-400 w-8">Oper.</span>
                        <div class="flex-1 bg-gray-100 rounded-full h-5 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 {{ $fila['margen_operativo'] >= 15 ? 'bg-blue-500' : ($fila['margen_operativo'] >= 5 ? 'bg-amber-500' : 'bg-red-500') }}"
                                 style="width: {{ min($fila['margen_operativo'], 100) }}%">
                            </div>
                        </div>
                        <span class="text-xs font-bold w-10 {{ $fila['margen_operativo'] >= 15 ? 'text-blue-600' : ($fila['margen_operativo'] >= 5 ? 'text-amber-600' : 'text-red-500') }}">
                            {{ $fila['margen_operativo'] }}%
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
        
        {{-- Leyenda del gráfico --}}
        <div class="flex flex-wrap gap-4 mt-4 pt-4 border-t border-gray-200 text-xs text-gray-500">
            <div class="flex items-center gap-1.5">
                <span class="w-3 h-3 bg-emerald-500 rounded-full"></span> Margen Bruto
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-3 h-3 bg-blue-500 rounded-full"></span> Margen Operativo
            </div>
            <span class="text-gray-300">|</span>
            <span>🟢 Excelente &nbsp; 🟡 Aceptable &nbsp; 🔴 Crítico</span>
        </div>
    </div>

    @endif

</div>
@endsection