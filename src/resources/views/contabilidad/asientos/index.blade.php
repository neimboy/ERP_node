@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- ENCABEZADO --}}
    <div class="page-header flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
            <span class="breadcrumb">Módulo de Contabilidad</span>
            <h2 class="title flex items-center gap-2">
                <span>📒</span> Asientos Contables
            </h2>
            <p class="subtitle">Registro del Libro Diario – Partida Doble</p>
        </div>
        
        {{-- Botones de acceso rápido --}}
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('asientos.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Nuevo Asiento
            </a>
            <div class="hidden sm:flex flex-wrap gap-2">
                <a href="{{ route('contabilidad.libro_mayor') }}" class="btn btn-ghost btn-sm text-gray-600" title="Libro Mayor">
                    <span>📗</span>
                </a>
                <a href="{{ route('contabilidad.balance_general') }}" class="btn btn-ghost btn-sm text-gray-600" title="Balance General">
                    <span>🏦</span>
                </a>
                <a href="{{ route('contabilidad.estado_resultados') }}" class="btn btn-ghost btn-sm text-gray-600" title="Estado de Resultados">
                    <span>📈</span>
                </a>
                <a href="{{ route('contabilidad.estado_resultados_semestral') }}" class="btn btn-ghost btn-sm text-gray-600" title="Resultado Semestral">
                    <span>📊</span>
                </a>
                <a href="{{ route('contabilidad.igv_mensual') }}" class="btn btn-ghost btn-sm text-gray-600" title="IGV Mensual">
                    <span>🧾</span>
                </a>
                <a href="{{ route('contabilidad.resumen_gerencial') }}" class="btn btn-ghost btn-sm text-blue-600" title="Resumen Gerencial">
                    <span>📋</span>
                </a>
                <a href="{{ route('contabilidad.plan_cuentas') }}" class="btn btn-ghost btn-sm text-gray-600" title="Plan de Cuentas">
                    <span>📒</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ALERTAS --}}
    @if(session('success'))
        <div class="alert alert-success animate-fade-in flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
    
    @if(session('warning'))
        <div class="alert alert-warning animate-fade-in flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ session('warning') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-amber-600 hover:text-amber-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- TABLA DE ASIENTOS --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th class="pl-5 w-16"># Asiento</th>
                        <th class="w-24">Fecha</th>
                        <th class="w-36">Período</th>
                        <th>Glosa / Descripción</th>
                        <th class="text-right w-36">Total Debe</th>
                        <th class="text-right w-36">Total Haber</th>
                        <th class="text-center w-20">Líneas</th>
                        <th class="text-center pr-5 w-28">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asientos as $asiento)
                        @php
                            $totalDebe  = $asiento->detalles->sum('Debe');
                            $totalHaber = $asiento->detalles->sum('Haber');
                            $cuadra     = abs($totalDebe - $totalHaber) <= 0.01;
                        @endphp
                        <tr class="asiento-row {{ $cuadra ? 'asiento-cuadrado' : 'asiento-descuadrado' }}">
                            {{-- Número de asiento --}}
                            <td class="pl-5">
                                <span class="asiento-numero">
                                    #{{ str_pad($asiento->Id_Asiento, 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            
                            {{-- Fecha --}}
                            <td>
                                <span class="font-mono text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($asiento->Fecha)->format('d/m/Y') }}
                                </span>
                            </td>
                            
                            {{-- Período --}}
                            <td>
                                @if($asiento->periodo)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium
                                        {{ $asiento->periodo->Estado === 'Abierto' ? 'periodo-abierto' : 'periodo-cerrado' }}">
                                        {{ $asiento->periodo->label ?? ($asiento->periodo->Año . '-' . str_pad($asiento->periodo->Mes, 2, '0', STR_PAD_LEFT)) }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">—</span>
                                @endif
                            </td>
                            
                            {{-- Glosa --}}
                            <td>
                                <span class="glosa-truncate block" title="{{ $asiento->Glosa }}">
                                    {{ $asiento->Glosa }}
                                </span>
                            </td>
                            
                            {{-- Total Debe --}}
                            <td class="text-right">
                                <span class="font-mono text-sm {{ $totalDebe > 0 ? 'text-debe' : 'text-gray-400' }}">
                                    {{ $totalDebe > 0 ? 'S/. ' . number_format($totalDebe, 2) : '—' }}
                                </span>
                            </td>
                            
                            {{-- Total Haber --}}
                            <td class="text-right">
                                <span class="font-mono text-sm {{ $totalHaber > 0 ? 'text-haber' : 'text-gray-400' }}">
                                    {{ $totalHaber > 0 ? 'S/. ' . number_format($totalHaber, 2) : '—' }}
                                </span>
                            </td>
                            
                            {{-- Cantidad de líneas --}}
                            <td class="text-center">
                                <span class="badge {{ $asiento->detalles->count() >= 2 ? 'badge-info' : 'badge-warning' }}">
                                    {{ $asiento->detalles->count() }}
                                </span>
                            </td>
                            
                            {{-- Acciones --}}
                            <td class="text-center pr-5">
                                <div class="flex items-center justify-center gap-1">
                                    @if(!$cuadra)
                                        <span class="text-red-500 text-sm" title="Asiento descuadrado">⚠️</span>
                                    @endif
                                    
                                    <a href="{{ route('asientos.show', $asiento->Id_Asiento) }}"
                                       class="btn btn-ghost btn-sm text-blue-600 hover:bg-blue-50"
                                       title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($asiento->periodo && $asiento->periodo->Estado === 'Abierto')
                                        <a href="{{ route('asientos.edit', $asiento->Id_Asiento) }}"
                                           class="btn btn-ghost btn-sm text-amber-600 hover:bg-amber-50"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                    
                                    <form action="{{ route('asientos.destroy', $asiento->Id_Asiento) }}"
                                          method="POST" class="inline"
                                          onsubmit="return confirm('¿Eliminar el asiento #{{ $asiento->Id_Asiento }}?\n\nEsta acción también eliminará sus {{ $asiento->detalles->count() }} líneas de detalle y no se puede deshacer.')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-ghost btn-sm text-red-500 hover:bg-red-50"
                                                title="Eliminar"
                                                {{ $asiento->periodo && $asiento->periodo->Estado !== 'Abierto' ? 'disabled' : '' }}>
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-16">
                                <div class="flex flex-col items-center">
                                    <span class="text-5xl mb-4">📭</span>
                                    <h3 class="text-lg font-semibold text-gray-600 mb-2">
                                        No hay asientos contables registrados
                                    </h3>
                                    <p class="text-gray-400 mb-4">
                                        Comienza registrando tu primer asiento en el libro diario
                                    </p>
                                    <a href="{{ route('asientos.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus mr-2"></i> Registrar Primer Asiento
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINACIÓN --}}
        @if($asientos->hasPages())
            <div class="px-5 py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-3">
                <span class="text-sm text-gray-500">
                    Mostrando 
                    <span class="font-medium text-gray-700">{{ $asientos->firstItem() }}</span> 
                    – 
                    <span class="font-medium text-gray-700">{{ $asientos->lastItem() }}</span> 
                    de 
                    <span class="font-medium text-gray-700">{{ $asientos->total() }}</span> 
                    asientos
                </span>
                <div class="flex gap-1">
                    {{ $asientos->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- LEYENDA --}}
    <div class="mt-4 bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="flex flex-wrap gap-4 text-sm text-gray-500">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 bg-emerald-500 rounded-full"></span>
                <span>Asiento cuadrado</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                <span>Asiento descuadrado</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                <span>Período abierto</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                <span>Período cerrado</span>
            </div>
            <span class="text-gray-400">|</span>
            <span>Solo se pueden editar/eliminar asientos de períodos abiertos</span>
        </div>
    </div>

</div>
@endsection