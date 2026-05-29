@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- ENCABEZADO --}}
    <div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="breadcrumb">Contabilidad / Asientos</span>
            <h2 class="title flex items-center gap-2">
                <span>📄</span> Asiento #{{ str_pad($asiento->Id_Asiento, 4, '0', STR_PAD_LEFT) }}
            </h2>
            <p class="subtitle">{{ $asiento->Glosa }}</p>
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

    {{-- ESTADO DEL ASIENTO --}}
    @php $cuadra = abs($totalDebe - $totalHaber) <= 0.01; @endphp
    
    @if($cuadra)
        <div class="alert alert-success mb-6 animate-fade-in">
            <div class="flex items-center gap-3">
                <span class="text-2xl">✅</span>
                <div>
                    <p class="font-semibold">Asiento Cuadrado Correctamente</p>
                    <p class="text-sm text-emerald-700">Partida Doble verificada — El Debe y Haber coinciden.</p>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-danger mb-6 animate-fade-in">
            <div class="flex items-center gap-3">
                <span class="text-2xl">⚠️</span>
                <div>
                    <p class="font-semibold">Asiento Descuadrado</p>
                    <p class="text-sm text-red-700">
                        Diferencia: <span class="font-bold">S/. {{ number_format(abs($totalDebe - $totalHaber), 2) }}</span> |
                        Debe: S/. {{ number_format($totalDebe, 2) }} |
                        Haber: S/. {{ number_format($totalHaber, 2) }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- CONTENIDO PRINCIPAL --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- COLUMNA IZQUIERDA: Datos del Asiento --}}
        <div class="lg:col-span-1">
            <div class="card h-full flex flex-col">
                
                {{-- Cabecera --}}
                <div class="bg-blue-600 text-white px-5 py-4 rounded-t-xl flex items-center gap-3">
                    <span class="text-xl">📋</span>
                    <span class="font-semibold">Datos del Asiento</span>
                </div>

                {{-- Cuerpo --}}
                <div class="p-5 flex-1 space-y-4">
                    
                    {{-- N° Asiento --}}
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">N° Asiento</span>
                        <span class="asiento-numero text-base">
                            #{{ str_pad($asiento->Id_Asiento, 4, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>

                    {{-- Fecha --}}
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Fecha</span>
                        <span class="font-mono text-sm text-gray-700">
                            {{ \Carbon\Carbon::parse($asiento->Fecha)->format('d/m/Y') }}
                        </span>
                    </div>

                    {{-- Período --}}
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Período</span>
                        <div class="text-right">
                            @if($asiento->periodo)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $asiento->periodo->Estado === 'Abierto' ? 'periodo-abierto' : 'periodo-cerrado' }}">
                                    {{ $asiento->periodo->label ?? ($asiento->periodo->Año . ' – Mes ' . $asiento->periodo->Mes) }}
                                </span>
                                <p class="text-xs text-gray-400 mt-1">
                                    Estado: {{ $asiento->periodo->Estado ?? 'N/D' }}
                                </p>
                            @else
                                <span class="text-gray-400 text-sm">Sin período asignado</span>
                            @endif
                        </div>
                    </div>

                    {{-- Tipo de Origen --}}
                    @if($asiento->Tipo_Origen)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Origen</span>
                        <span class="badge badge-purple text-xs">
                            {{ $asiento->etiqueta_origen ?? $asiento->Tipo_Origen }}
                        </span>
                    </div>
                    @endif

                    {{-- Glosa --}}
                    <div class="py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500 block mb-1">Glosa</span>
                        <p class="text-sm text-gray-700">{{ $asiento->Glosa }}</p>
                    </div>

                    {{-- Fechas de registro --}}
                    <div class="py-2">
                        <span class="text-sm text-gray-500 block mb-1">Registrado</span>
                        <p class="text-xs text-gray-400">
                            {{ $asiento->created_at?->format('d/m/Y H:i') ?? '—' }}
                        </p>
                        @if($asiento->updated_at && $asiento->updated_at->ne($asiento->created_at))
                            <p class="text-xs text-gray-400 mt-0.5">
                                Actualizado: {{ $asiento->updated_at->format('d/m/Y H:i') }}
                            </p>
                        @endif
                    </div>
                </div>

                {{-- PIE: Totales --}}
                <div class="mt-auto bg-gray-50 rounded-b-xl p-5 space-y-3 border-t border-gray-200">
                    
                    {{-- Total Debe --}}
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Debe</span>
                        <span class="font-mono font-bold text-emerald-600">
                            S/. {{ number_format($totalDebe, 2) }}
                        </span>
                    </div>

                    {{-- Total Haber --}}
                    <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Haber</span>
                        <span class="font-mono font-bold text-red-500">
                            S/. {{ number_format($totalHaber, 2) }}
                        </span>
                    </div>

                    {{-- Diferencia --}}
                    <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                        <span class="text-xs font-semibold uppercase tracking-wider {{ $cuadra ? 'text-emerald-600' : 'text-red-500' }}">
                            {{ $cuadra ? '✅ Cuadrado' : '⚠️ Diferencia' }}
                        </span>
                        <span class="font-mono font-bold {{ $cuadra ? 'text-emerald-600' : 'text-red-500' }}">
                            S/. {{ number_format(abs($totalDebe - $totalHaber), 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: Líneas de Detalle --}}
        <div class="lg:col-span-2">
            <div class="card">
                
                {{-- Cabecera --}}
                <div class="bg-gray-800 text-white px-5 py-4 rounded-t-xl flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">📊</span>
                        <span class="font-semibold">
                            Líneas Contables 
                            <span class="text-gray-300 text-sm font-normal">({{ $asiento->detalles->count() }} partidas)</span>
                        </span>
                    </div>
                    <span class="badge bg-white/20 text-white text-xs">
                        Partida Doble
                    </span>
                </div>

                {{-- Tabla de líneas --}}
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="pl-5 w-24">Código</th>
                                <th>Cuenta Contable</th>
                                <th class="text-center w-24">Tipo</th>
                                <th class="text-right w-36">Debe S/.</th>
                                <th class="text-right pr-5 w-36">Haber S/.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($asiento->detalles as $detalle)
                                @php
                                    $tipo = $detalle->cuenta->Tipo ?? '';
                                    $tipoBadge = match($tipo) {
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
                                <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                                    {{-- Código --}}
                                    <td class="pl-5">
                                        <span class="font-mono font-bold text-sm text-blue-600">
                                            {{ $detalle->cuenta->Codigo ?? '—' }}
                                        </span>
                                    </td>

                                    {{-- Nombre de la cuenta --}}
                                    <td class="text-gray-700">
                                        {{ $detalle->cuenta->Nombre_Cuenta ?? 'Cuenta no encontrada' }}
                                    </td>

                                    {{-- Tipo --}}
                                    <td class="text-center">
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $tipoBadge }}">
                                            {{ $tipo ?: 'N/D' }}
                                        </span>
                                    </td>

                                    {{-- Debe --}}
                                    <td class="text-right">
                                        @if($detalle->Debe > 0)
                                            <span class="font-mono font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded text-sm">
                                                S/. {{ number_format($detalle->Debe, 2) }}
                                            </span>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>

                                    {{-- Haber --}}
                                    <td class="text-right pr-5">
                                        @if($detalle->Haber > 0)
                                            <span class="font-mono font-semibold text-red-500 bg-red-50 px-2 py-1 rounded text-sm">
                                                S/. {{ number_format($detalle->Haber, 2) }}
                                            </span>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-12">
                                        <div class="flex flex-col items-center text-gray-400">
                                            <span class="text-3xl mb-2">📝</span>
                                            <p>Este asiento no tiene líneas de detalle.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50 border-t-2 border-gray-200">
                                <td colspan="3" class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider pl-5 py-3">
                                    Totales:
                                </td>
                                <td class="text-right font-mono font-bold text-emerald-600 py-3">
                                    S/. {{ number_format($totalDebe, 2) }}
                                </td>
                                <td class="text-right font-mono font-bold text-red-500 pr-5 py-3">
                                    S/. {{ number_format($totalHaber, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- BOTONES DE ACCIÓN --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-4">
                <div class="flex gap-2">
                    <a href="{{ route('asientos.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Volver a la lista
                    </a>
                    <a href="{{ route('asientos.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Nuevo Asiento
                    </a>
                    
                    @if($asiento->periodo && $asiento->periodo->Estado === 'Abierto')
                        <a href="{{ route('asientos.edit', $asiento->Id_Asiento) }}" class="btn bg-amber-500 text-white hover:bg-amber-600 btn-sm">
                            <i class="fas fa-edit mr-1"></i> Editar
                        </a>
                    @endif
                </div>
                
                <form action="{{ route('asientos.destroy', $asiento->Id_Asiento) }}"
                      method="POST"
                      onsubmit="return confirm('¿Eliminar este asiento y todas sus {{ $asiento->detalles->count() }} líneas?\n\nEsta acción no se puede deshacer.')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="btn btn-danger btn-sm"
                            {{ $asiento->periodo && $asiento->periodo->Estado !== 'Abierto' ? 'disabled' : '' }}>
                        <i class="fas fa-trash-alt mr-1"></i> Eliminar Asiento
                    </button>
                </form>
            </div>
            
            {{-- Aviso de período cerrado --}}
            @if($asiento->periodo && $asiento->periodo->Estado !== 'Abierto')
                <div class="mt-3 bg-amber-50 border border-amber-200 rounded-lg p-3 flex items-center gap-2 text-sm text-amber-700">
                    <i class="fas fa-lock"></i>
                    <span>Este asiento pertenece a un período <strong>cerrado</strong>. No se puede editar ni eliminar.</span>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection