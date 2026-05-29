@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- ENCABEZADO --}}
    <div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="breadcrumb">Estados Financieros</span>
            <h2 class="title flex items-center gap-2">
                <span>🏦</span> Balance General
            </h2>
            <p class="subtitle">Ecuación Contable: ACTIVO = PASIVO + PATRIMONIO | Expresado en Soles (S/.)</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="btn btn-secondary btn-sm">
                <i class="fas fa-print mr-1"></i> Imprimir Balance
            </button>
            <a href="{{ route('asientos.index') }}" class="btn btn-ghost btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
        </div>
    </div>

    {{-- ESTADO DEL BALANCE --}}
    @php $cuadra = abs($totalActivo - $totalPasivoYPatrimonio) <= 0.05; @endphp
    
    @if(!$cuadra)
        <div class="alert alert-warning mb-6 animate-fade-in">
            <div class="flex items-start gap-3">
                <span class="text-2xl">⚠️</span>
                <div>
                    <p class="font-semibold">Aviso de Descuadre Contable</p>
                    <p class="text-sm text-amber-700">
                        La suma total del Activo no coincide exactamente con el Pasivo + Patrimonio por una diferencia de 
                        <span class="font-bold">S/. {{ number_format(abs($totalActivo - $totalPasivoYPatrimonio), 2) }}</span>.
                        Por favor revise los asientos diarios de regularización.
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-success mb-6 animate-fade-in">
            <div class="flex items-center gap-3">
                <span class="text-2xl">✅</span>
                <div>
                    <p class="font-semibold">¡Balance Cuadrado Correctamente!</p>
                    <p class="text-sm text-emerald-700">Bajo el principio de partida doble — Activo = Pasivo + Patrimonio.</p>
                </div>
            </div>
        </div>
    @endif

    {{-- ECUACIÓN CONTABLE VISUAL --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 text-center">
            <div class="bg-blue-50 rounded-xl px-6 py-3 flex-1">
                <span class="text-xs text-blue-500 uppercase tracking-wider font-semibold">Total Activo</span>
                <div class="text-2xl font-bold text-blue-700 font-mono">S/. {{ number_format($totalActivo, 2) }}</div>
            </div>
            <div class="text-2xl font-bold text-gray-400">=</div>
            <div class="bg-orange-50 rounded-xl px-6 py-3 flex-1">
                <span class="text-xs text-orange-500 uppercase tracking-wider font-semibold">Total Pasivo</span>
                <div class="text-2xl font-bold text-orange-700 font-mono">S/. {{ number_format($totalPasivo, 2) }}</div>
            </div>
            <div class="text-2xl font-bold text-gray-400">+</div>
            <div class="bg-purple-50 rounded-xl px-6 py-3 flex-1">
                <span class="text-xs text-purple-500 uppercase tracking-wider font-semibold">Total Patrimonio</span>
                <div class="text-2xl font-bold text-purple-700 font-mono">S/. {{ number_format($totalPatrimonio, 2) }}</div>
            </div>
        </div>
    </div>

    {{-- CONTENIDO PRINCIPAL: DOS COLUMNAS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- COLUMNA IZQUIERDA: ACTIVO --}}
        <div>
            <div class="card balance-activo-section h-full">
                
                {{-- Cabecera --}}
                <div class="bg-emerald-600 text-white px-5 py-4 rounded-t-xl flex items-center gap-3">
                    <span class="text-xl">📈</span>
                    <span class="font-semibold uppercase tracking-wider text-sm">Estructura del Activo</span>
                </div>

                {{-- Tabla de activos --}}
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="pl-5">Cuenta / Concepto</th>
                                <th class="text-right pr-5 w-40">Monto Actual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activos as $activo)
                                <tr class="hover:bg-emerald-50/30 transition-colors duration-150">
                                    <td class="pl-5">
                                        <span class="font-mono text-xs text-emerald-600 font-bold mr-2">
                                            {{ $activo['codigo'] }}
                                        </span>
                                        <span class="text-gray-700">{{ $activo['nombre'] }}</span>
                                    </td>
                                    <td class="text-right pr-5">
                                        <span class="font-mono text-sm font-medium {{ $activo['monto'] < 0 ? 'text-red-500' : 'text-emerald-700' }}">
                                            {{ $activo['monto'] < 0 ? '(S/. ' . number_format(abs($activo['monto']), 2) . ')' : 'S/. ' . number_format($activo['monto'], 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-8">
                                        <div class="flex flex-col items-center text-gray-400">
                                            <span class="text-3xl mb-2">📋</span>
                                            <p class="text-sm">No hay registros de cuentas de Activos.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pie: Total Activo --}}
                <div class="mt-auto bg-emerald-50 rounded-b-xl px-5 py-4 border-t-2 border-emerald-200 flex justify-between items-center">
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Total Activo</span>
                    <span class="font-mono text-lg font-bold text-emerald-700">
                        S/. {{ number_format($totalActivo, 2) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: PASIVO + PATRIMONIO --}}
        <div>
            <div class="card h-full flex flex-col">
                
                {{-- SECCIÓN PASIVO --}}
                <div class="bg-orange-600 text-white px-5 py-4 rounded-t-xl flex items-center gap-3">
                    <span class="text-xl">📉</span>
                    <span class="font-semibold uppercase tracking-wider text-sm">Estructura del Pasivo</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="pl-5">Cuenta / Concepto</th>
                                <th class="text-right pr-5 w-40">Monto Actual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pasivos as $pasivo)
                                <tr class="hover:bg-orange-50/30 transition-colors duration-150">
                                    <td class="pl-5">
                                        <span class="font-mono text-xs text-orange-600 font-bold mr-2">
                                            {{ $pasivo['codigo'] }}
                                        </span>
                                        <span class="text-gray-700">{{ $pasivo['nombre'] }}</span>
                                    </td>
                                    <td class="text-right pr-5">
                                        <span class="font-mono text-sm font-medium text-orange-700">
                                            S/. {{ number_format($pasivo['monto'], 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-4">
                                        <p class="text-sm text-gray-400">No hay deudas ni obligaciones registradas.</p>
                                    </td>
                                </tr>
                            @endforelse

                            {{-- Subtotal Pasivo --}}
                            <tr class="bg-orange-50 border-t border-orange-200">
                                <td class="pl-5 text-xs font-semibold text-orange-700 uppercase tracking-wider py-3">
                                    Subtotal Pasivos
                                </td>
                                <td class="text-right pr-5 font-mono font-bold text-orange-700 py-3">
                                    S/. {{ number_format($totalPasivo, 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- SECCIÓN PATRIMONIO --}}
                <div class="bg-purple-600 text-white px-5 py-3 flex items-center gap-3">
                    <span class="text-lg">🏛️</span>
                    <span class="font-semibold uppercase tracking-wider text-sm">Estructura del Patrimonio</span>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table class="table">
                        <tbody>
                            @forelse($patrimonio as $pat)
                                <tr class="hover:bg-purple-50/30 transition-colors duration-150">
                                    <td class="pl-5">
                                        <span class="font-mono text-xs text-purple-600 font-bold mr-2">
                                            {{ $pat['codigo'] }}
                                        </span>
                                        <span class="text-gray-700">{{ $pat['nombre'] }}</span>
                                    </td>
                                    <td class="text-right pr-5">
                                        <span class="font-mono text-sm font-medium text-purple-700">
                                            S/. {{ number_format($pat['monto'], 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-4">
                                        <p class="text-sm text-gray-400">No se registran cuentas patrimoniales.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pie: Total Pasivo + Patrimonio --}}
                <div class="mt-auto bg-gray-800 rounded-b-xl px-5 py-4 flex justify-between items-center">
                    <span class="text-xs font-bold text-gray-300 uppercase tracking-wider">Total Pasivo + Patrimonio</span>
                    <span class="font-mono text-lg font-bold text-white">
                        S/. {{ number_format($totalPasivoYPatrimonio, 2) }}
                    </span>
                </div>
            </div>
        </div>

    </div>

    {{-- INDICADOR DE CUADRE --}}
    <div class="mt-6 text-center">
        @if($cuadra)
            <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 px-6 py-3 rounded-xl border border-emerald-200">
                <span class="text-xl">✅</span>
                <span class="font-semibold">Balance Cuadrado — Activo = Pasivo + Patrimonio</span>
            </div>
        @else
            <div class="inline-flex items-center gap-2 bg-amber-50 text-amber-700 px-6 py-3 rounded-xl border border-amber-200">
                <span class="text-xl">⚠️</span>
                <span class="font-semibold">Diferencia detectada — Revisar asientos de regularización</span>
            </div>
        @endif
    </div>

    {{-- NOTA ACLARATORIA --}}
    <div class="mt-4 bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm text-gray-500">
        <div class="flex items-start gap-2">
            <i class="fas fa-info-circle text-gray-400 mt-0.5"></i>
            <p>
                <strong>Base contable:</strong> Este balance general se elabora siguiendo el Plan Contable General Empresarial (PCGE) 
                y el principio de partida doble. Los saldos se calculan como la diferencia entre movimientos deudores y acreedores 
                de cada cuenta contable.
            </p>
        </div>
    </div>

</div>
@endsection