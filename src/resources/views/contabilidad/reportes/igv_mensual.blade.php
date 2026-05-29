@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- ENCABEZADO --}}
    <div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="breadcrumb">Módulo de Contabilidad</span>
            <h2 class="title flex items-center gap-2">
                <span>🧾</span> Declaración Mensual de IGV
            </h2>
            <p class="subtitle">PDT 621 – SUNAT | Tasa: 18% | Débito Fiscal vs. Crédito Fiscal</p>
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

    {{-- ALERTA DE RESULTADO GLOBAL --}}
    @if($igvAPagar > 0)
        <div class="alert alert-warning mb-6 animate-fade-in">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center">
                    <span class="text-2xl">⚠️</span>
                </div>
                <div>
                    <p class="font-semibold text-amber-800">IGV por Pagar a SUNAT</p>
                    <p class="text-2xl font-bold text-amber-700 font-mono">
                        S/. {{ number_format($igvAPagar, 2) }}
                    </p>
                    <p class="text-sm text-amber-600 mt-0.5">El débito fiscal supera al crédito fiscal</p>
                </div>
            </div>
        </div>
    @elseif($igvAPagar < 0)
        <div class="alert alert-success mb-6 animate-fade-in">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                    <span class="text-2xl">✅</span>
                </div>
                <div>
                    <p class="font-semibold text-emerald-800">Saldo a Favor del Contribuyente</p>
                    <p class="text-2xl font-bold text-emerald-700 font-mono">
                        S/. {{ number_format(abs($igvAPagar), 2) }}
                    </p>
                    <p class="text-sm text-emerald-600 mt-0.5">Se arrastra al siguiente período</p>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info mb-6 animate-fade-in">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-sky-100 rounded-full flex items-center justify-center">
                    <span class="text-2xl">ℹ️</span>
                </div>
                <div>
                    <p class="font-semibold text-sky-800">IGV Equilibrado</p>
                    <p class="text-sm text-sky-600">No hay monto a pagar ni saldo a favor en este período.</p>
                </div>
            </div>
        </div>
    @endif

    {{-- RESUMEN RÁPIDO --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="kpi-card text-center">
            <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">
                <i class="fas fa-arrow-up text-blue-500 mr-1"></i> IGV Ventas (Débito)
            </span>
            <div class="text-xl font-bold text-blue-600 font-mono mt-1">
                S/. {{ number_format($igvVentasTotal, 2) }}
            </div>
        </div>
        <div class="kpi-card text-center">
            <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">
                <i class="fas fa-arrow-down text-emerald-500 mr-1"></i> IGV Compras (Crédito)
            </span>
            <div class="text-xl font-bold text-emerald-600 font-mono mt-1">
                S/. {{ number_format($igvComprasTotal, 2) }}
            </div>
        </div>
        <div class="kpi-card text-center {{ $igvAPagar > 0 ? 'border-l-4 border-amber-500' : ($igvAPagar < 0 ? 'border-l-4 border-emerald-500' : '') }}">
            <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">
                <i class="fas fa-calculator text-gray-500 mr-1"></i> IGV Neto
            </span>
            <div class="text-xl font-bold font-mono mt-1 {{ $igvAPagar > 0 ? 'text-amber-600' : ($igvAPagar < 0 ? 'text-emerald-600' : 'text-gray-600') }}">
                {{ $igvAPagar > 0 ? 'S/. ' . number_format($igvAPagar, 2) : '' }}
                {{ $igvAPagar < 0 ? '(S/. ' . number_format(abs($igvAPagar), 2) . ')' : '' }}
                {{ $igvAPagar == 0 ? 'S/. 0.00' : '' }}
            </div>
        </div>
    </div>

    {{-- DETALLE MENSUAL --}}
    @if(!empty($filasMensuales))
    <div class="card overflow-hidden">
        
        {{-- Cabecera --}}
        <div class="bg-gray-800 text-white px-5 py-4 rounded-t-xl flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-xl">📅</span>
                <span class="font-semibold">Detalle Mensual</span>
            </div>
            <span class="badge bg-white/20 text-white text-xs">
                {{ count($filasMensuales) }} períodos
            </span>
        </div>

        {{-- Tabla --}}
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th class="pl-5 text-left w-36">Período</th>
                        <th class="text-right w-40">IGV Ventas (Débito)</th>
                        <th class="text-right w-40">IGV Compras (Crédito)</th>
                        <th class="text-right w-40">IGV Neto</th>
                        <th class="text-center pr-5 w-28">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($filasMensuales as $fila)
                        <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                            {{-- Período --}}
                            <td class="pl-5">
                                <span class="font-semibold text-gray-700">{{ $fila['label'] }}</span>
                            </td>

                            {{-- IGV Ventas --}}
                            <td class="text-right">
                                @if($fila['igv_ventas'] > 0)
                                    <span class="font-mono text-sm font-medium text-blue-600">
                                        S/. {{ number_format($fila['igv_ventas'], 2) }}
                                    </span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>

                            {{-- IGV Compras --}}
                            <td class="text-right">
                                @if($fila['igv_compras'] > 0)
                                    <span class="font-mono text-sm font-medium text-emerald-600">
                                        S/. {{ number_format($fila['igv_compras'], 2) }}
                                    </span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>

                            {{-- IGV Neto --}}
                            <td class="text-right">
                                @if($fila['igv_neto'] != 0)
                                    <span class="font-mono text-sm font-bold {{ $fila['igv_neto'] > 0 ? 'text-amber-600' : 'text-emerald-600' }}">
                                        {{ $fila['igv_neto'] > 0 ? '▲' : '▼' }}
                                        S/. {{ number_format(abs($fila['igv_neto']), 2) }}
                                    </span>
                                @else
                                    <span class="text-gray-400 font-mono text-sm">S/. 0.00</span>
                                @endif
                            </td>

                            {{-- Estado --}}
                            <td class="text-center pr-5">
                                @if($fila['igv_neto'] > 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                        <i class="fas fa-exclamation-triangle text-xs"></i> A Pagar
                                    </span>
                                @elseif($fila['igv_neto'] < 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                        <i class="fas fa-check-circle text-xs"></i> A Favor
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                        <i class="fas fa-equals text-xs"></i> Neutro
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                {{-- PIE: TOTALES --}}
                <tfoot>
                    <tr class="bg-gray-800 text-white">
                        <td class="pl-5 py-3 font-bold text-sm uppercase tracking-wider">
                            Total Acumulado
                        </td>
                        <td class="text-right font-mono font-bold text-blue-400">
                            S/. {{ number_format($igvVentasTotal, 2) }}
                        </td>
                        <td class="text-right font-mono font-bold text-emerald-400">
                            S/. {{ number_format($igvComprasTotal, 2) }}
                        </td>
                        <td class="text-right font-mono font-bold {{ $igvAPagar > 0 ? 'text-amber-400' : 'text-emerald-400' }}">
                            {{ $igvAPagar > 0 ? '▲ ' : ($igvAPagar < 0 ? '▼ ' : '') }}
                            S/. {{ number_format(abs($igvAPagar), 2) }}
                        </td>
                        <td class="text-center pr-5">
                            @if($igvAPagar > 0)
                                <span class="badge bg-amber-500 text-white text-xs">A Pagar</span>
                            @elseif($igvAPagar < 0)
                                <span class="badge bg-emerald-500 text-white text-xs">A Favor</span>
                            @else
                                <span class="badge bg-gray-500 text-white text-xs">Neutro</span>
                            @endif
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @else
        {{-- Estado vacío --}}
        <div class="text-center py-16">
            <span class="text-6xl block mb-4">🧾</span>
            <h3 class="text-lg font-semibold text-gray-600 mb-2">No hay datos de IGV registrados</h3>
            <p class="text-gray-400">Registra asientos con la cuenta 40 (IGV) para visualizar la declaración mensual.</p>
        </div>
    @endif

    {{-- FÓRMULA EXPLICATIVA --}}
    <div class="mt-6 bg-white border border-gray-200 rounded-xl p-5">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                <span class="text-blue-600 text-lg">📌</span>
            </div>
            <div>
                <h4 class="font-semibold text-gray-700 mb-2">Fórmula PDT 621</h4>
                <div class="bg-gray-50 rounded-lg p-4 font-mono text-sm">
                    <div class="flex flex-wrap items-center gap-2 text-gray-600">
                        <span class="font-bold text-gray-800">IGV Neto</span>
                        <span class="text-gray-400">=</span>
                        <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded font-bold">IGV Ventas (Débito Fiscal)</span>
                        <span class="text-gray-400">−</span>
                        <span class="bg-emerald-50 text-emerald-700 px-2 py-1 rounded font-bold">IGV Compras (Crédito Fiscal)</span>
                    </div>
                </div>
                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-gray-500">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                        <span><strong>Positivo:</strong> Monto a pagar a SUNAT</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                        <span><strong>Negativo:</strong> Saldo a favor que se arrastra al siguiente mes</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- INDICADOR DE TASA --}}
    <div class="mt-4 text-center text-xs text-gray-400">
        <span>Tasa IGV: 18% | Base Legal: D.S. N° 055-99-EF | PDT 621 – SUNAT</span>
    </div>

</div>
@endsection