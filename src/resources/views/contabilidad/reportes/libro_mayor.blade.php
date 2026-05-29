@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- ENCABEZADO --}}
    <div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="breadcrumb">Módulo de Contabilidad</span>
            <h2 class="title flex items-center gap-2">
                <span>📗</span> Libro Mayor
            </h2>
            <p class="subtitle">Acumulado de movimientos y saldos por cuenta contable (PCGE)</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="btn btn-secondary btn-sm">
                <i class="fas fa-print mr-1"></i> Imprimir Reporte
            </button>
            <a href="{{ route('asientos.index') }}" class="btn btn-ghost btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
        </div>
    </div>

    {{-- RESUMEN RÁPIDO --}}
    @if($cuentasMayor->count() > 0)
        @php
            $cuentasConMov = $cuentasMayor->filter(fn($c) => $c['total_debe'] > 0 || $c['total_haber'] > 0)->count();
            $cuentasSinMov = $cuentasMayor->count() - $cuentasConMov;
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            <div class="kpi-card text-center">
                <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Total Cuentas</span>
                <div class="text-xl font-bold text-gray-700 mt-1">{{ $cuentasMayor->count() }}</div>
            </div>
            <div class="kpi-card text-center border-l-4 border-blue-500">
                <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Con Movimientos</span>
                <div class="text-xl font-bold text-blue-600 mt-1">{{ $cuentasConMov }}</div>
            </div>
            <div class="kpi-card text-center border-l-4 border-gray-400">
                <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Sin Movimientos</span>
                <div class="text-xl font-bold text-gray-400 mt-1">{{ $cuentasSinMov }}</div>
            </div>
            <div class="kpi-card text-center border-l-4 border-emerald-500">
                <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Total Movimientos</span>
                <div class="text-xl font-bold text-emerald-600 mt-1">
                    S/. {{ number_format($cuentasMayor->sum('total_debe') + $cuentasMayor->sum('total_haber'), 2) }}
                </div>
            </div>
        </div>
    @endif

    {{-- TABLA DEL LIBRO MAYOR --}}
    <div class="card overflow-hidden">
        
        {{-- Cabecera --}}
        <div class="bg-gray-800 text-white px-5 py-4 rounded-t-xl flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-xl">📗</span>
                <div>
                    <span class="font-semibold">Libro Mayor</span>
                    <p class="text-gray-400 text-xs mt-0.5">Saldos acumulados por cuenta contable</p>
                </div>
            </div>
            <span class="badge bg-white/20 text-white text-xs">
                {{ $cuentasMayor->count() }} cuentas
            </span>
        </div>

        {{-- Tabla --}}
        <div class="overflow-x-auto">
            <table class="table min-w-[1000px]">
                <thead>
                    <tr>
                        <th class="pl-5 text-left w-24">Código</th>
                        <th class="text-left w-64">Cuenta Contable</th>
                        <th class="text-center w-28">Tipo</th>
                        <th class="text-right w-36">Total Debe</th>
                        <th class="text-right w-36">Total Haber</th>
                        <th class="text-right w-36">Saldo Deudor</th>
                        <th class="text-right pr-5 w-36">Saldo Acreedor</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cuentasMayor as $cuenta)
                        @php
                            $sinMovimientos = $cuenta['total_debe'] == 0 && $cuenta['total_haber'] == 0;
                            $tipoBadge = match($cuenta['tipo']) {
                                'Activo'          => 'bg-blue-100 text-blue-700',
                                'Activo (Contra)' => 'bg-amber-100 text-amber-700',
                                'Pasivo'          => 'bg-orange-100 text-orange-700',
                                'Patrimonio'      => 'bg-purple-100 text-purple-700',
                                'Ingreso'         => 'bg-teal-100 text-teal-700',
                                'Gasto'           => 'bg-gray-100 text-gray-600',
                                'Costo'           => 'bg-rose-100 text-rose-700',
                                default           => 'bg-gray-50 text-gray-500',
                            };
                        @endphp
                        <tr class="transition-colors duration-150 {{ $sinMovimientos ? 'bg-gray-50/50 text-gray-400' : 'hover:bg-gray-50/50' }}">
                            
                            {{-- Código --}}
                            <td class="pl-5">
                                <span class="font-mono font-bold text-sm {{ $sinMovimientos ? 'text-gray-400' : 'text-blue-600' }}">
                                    {{ $cuenta['codigo'] }}
                                </span>
                            </td>

                            {{-- Nombre de la cuenta --}}
                            <td>
                                <span class="{{ $sinMovimientos ? '' : 'font-semibold text-gray-700' }}">
                                    {{ $cuenta['nombre'] }}
                                </span>
                            </td>

                            {{-- Tipo --}}
                            <td class="text-center">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tipoBadge }}">
                                    {{ $cuenta['tipo'] }}
                                </span>
                            </td>

                            {{-- Total Debe --}}
                            <td class="text-right">
                                @if($cuenta['total_debe'] > 0)
                                    <span class="font-mono text-sm font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded">
                                        S/. {{ number_format($cuenta['total_debe'], 2) }}
                                    </span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>

                            {{-- Total Haber --}}
                            <td class="text-right">
                                @if($cuenta['total_haber'] > 0)
                                    <span class="font-mono text-sm font-medium text-red-500 bg-red-50 px-2 py-1 rounded">
                                        S/. {{ number_format($cuenta['total_haber'], 2) }}
                                    </span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>

                            {{-- Saldo Deudor --}}
                            <td class="text-right">
                                @if($cuenta['saldo_deudor'] > 0)
                                    <span class="font-mono text-sm font-bold text-emerald-700">
                                        S/. {{ number_format($cuenta['saldo_deudor'], 2) }}
                                    </span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>

                            {{-- Saldo Acreedor --}}
                            <td class="text-right pr-5">
                                @if($cuenta['saldo_acreedor'] > 0)
                                    <span class="font-mono text-sm font-bold text-red-600">
                                        S/. {{ number_format($cuenta['saldo_acreedor'], 2) }}
                                    </span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-16">
                                <div class="flex flex-col items-center">
                                    <span class="text-5xl mb-4">📗</span>
                                    <h3 class="text-lg font-semibold text-gray-600 mb-2">
                                        No se encontraron cuentas contables
                                    </h3>
                                    <p class="text-gray-400">
                                        Registra asientos contables para visualizar los saldos en el Libro Mayor.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                {{-- PIE: TOTALES GENERALES --}}
                @if($cuentasMayor->count() > 0)
                <tfoot>
                    <tr class="bg-gray-800 text-white">
                        <td colspan="3" class="pl-5 py-3 text-sm font-bold uppercase tracking-wider text-right">
                            Totales Generales del Mayor:
                        </td>
                        <td class="text-right font-mono font-bold text-emerald-400">
                            S/. {{ number_format($cuentasMayor->sum('total_debe'), 2) }}
                        </td>
                        <td class="text-right font-mono font-bold text-red-400">
                            S/. {{ number_format($cuentasMayor->sum('total_haber'), 2) }}
                        </td>
                        <td class="text-right font-mono font-bold text-emerald-400">
                            S/. {{ number_format($cuentasMayor->sum('saldo_deudor'), 2) }}
                        </td>
                        <td class="text-right font-mono font-bold text-red-400 pr-5">
                            S/. {{ number_format($cuentasMayor->sum('saldo_acreedor'), 2) }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- LEYENDA DE TIPOS DE CUENTA --}}
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="text-sm font-semibold text-gray-600 mb-3 flex items-center gap-2">
            <span class="w-1 h-4 bg-gray-400 rounded-full"></span>
            Tipos de Cuenta
        </h3>
        <div class="flex flex-wrap gap-3">
            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Activo</span>
            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">Activo (Contra)</span>
            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700">Pasivo</span>
            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">Patrimonio</span>
            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-700">Ingreso</span>
            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Gasto</span>
            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-700">Costo</span>
        </div>
        <div class="mt-3 flex flex-wrap gap-4 text-xs text-gray-500">
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                <span>Saldo Deudor (Activo/Gasto)</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                <span>Saldo Acreedor (Pasivo/Patrimonio/Ingreso)</span>
            </div>
        </div>
    </div>

    {{-- NOTA ACLARATORIA --}}
    <div class="mt-3 text-center text-xs text-gray-400">
        <span>Los saldos se calculan como la diferencia entre movimientos deudores y acreedores de cada cuenta contable.</span>
    </div>

</div>
@endsection