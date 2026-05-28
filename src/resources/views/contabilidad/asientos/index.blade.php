@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-4">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <span class="text-uppercase text-muted small fw-bold">Módulo de Contabilidad</span>
            <h2 class="h3 mb-0 fw-bold">📒 Asientos Contables</h2>
            <p class="text-muted mb-0">Registro del Libro Diario – Partida Doble</p>
        </div>
        <div class="d-flex gap-2 flex-wrap justify-content-end">
            <a href="{{ route('asientos.create') }}" class="btn btn-primary btn-sm">+ Nuevo Asiento</a>
            <a href="{{ route('contabilidad.libro_mayor') }}" class="btn btn-outline-secondary btn-sm">📗 Libro Mayor</a>
            <a href="{{ route('contabilidad.balance_general') }}" class="btn btn-outline-secondary btn-sm">🏦 Balance</a>
            <a href="{{ route('contabilidad.estado_resultados') }}" class="btn btn-outline-secondary btn-sm">📈 Resultados</a>
            <a href="{{ route('contabilidad.estado_resultados_semestral') }}" class="btn btn-outline-secondary btn-sm">📊 Semestral</a>
            <a href="{{ route('contabilidad.igv_mensual') }}" class="btn btn-outline-secondary btn-sm">🧾 IGV</a>
            <a href="{{ route('contabilidad.resumen_gerencial') }}" class="btn btn-outline-info btn-sm">📋 Gerencial</a>
            <a href="{{ route('contabilidad.plan_cuentas') }}" class="btn btn-outline-dark btn-sm">📒 Plan Cuentas</a>
        </div>
    </div>

    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show shadow-sm">
            ⚠️ {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabla de asientos --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark text-uppercase" style="font-size: 0.8rem;">
                        <tr>
                            <th class="ps-4 py-3" style="width: 6%;"># Asiento</th>
                            <th class="py-3" style="width: 10%;">Fecha</th>
                            <th class="py-3" style="width: 15%;">Período</th>
                            <th class="py-3">Glosa / Descripción</th>
                            <th class="py-3 text-end" style="width: 12%;">Total Debe</th>
                            <th class="py-3 text-end" style="width: 12%;">Total Haber</th>
                            <th class="py-3 text-center" style="width: 8%;">Líneas</th>
                            <th class="py-3 text-center pe-4" style="width: 10%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asientos as $asiento)
                        @php
                            $totalDebe  = $asiento->detalles->sum('Debe');
                            $totalHaber = $asiento->detalles->sum('Haber');
                            $cuadra     = abs($totalDebe - $totalHaber) <= 0.01;
                        @endphp
                        <tr>
                            <td class="ps-4 fw-bold font-monospace text-primary">
                                #{{ str_pad($asiento->Id_Asiento, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="font-monospace">
                                {{ \Carbon\Carbon::parse($asiento->Fecha)->format('d/m/Y') }}
                            </td>
                            <td>
                                @if($asiento->periodo)
                                    <span class="badge bg-light text-dark border">
                                        {{ $asiento->periodo->label ?? ($asiento->periodo->Año . '-' . str_pad($asiento->periodo->Mes, 2, '0', STR_PAD_LEFT)) }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 350px;" title="{{ $asiento->Glosa }}">
                                    {{ $asiento->Glosa }}
                                </span>
                            </td>
                            <td class="text-end font-monospace pe-3">
                                S/. {{ number_format($totalDebe, 2) }}
                            </td>
                            <td class="text-end font-monospace pe-3">
                                S/. {{ number_format($totalHaber, 2) }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary rounded-pill">{{ $asiento->detalles->count() }}</span>
                            </td>
                            <td class="text-center pe-4">
                                @if(!$cuadra)
                                    <span class="badge bg-danger me-1" title="Asiento descuadrado">⚠</span>
                                @endif
                                <a href="{{ route('asientos.show', $asiento->Id_Asiento) }}"
                                   class="btn btn-xs btn-outline-primary btn-sm py-0 px-2">Ver</a>
                                <form action="{{ route('asientos.destroy', $asiento->Id_Asiento) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar el asiento #{{ $asiento->Id_Asiento }}? Esta acción también eliminará sus líneas de detalle.')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2">✕</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <p class="fs-3 mb-2">📭</p>
                                <p class="mb-1">No hay asientos contables registrados.</p>
                                <a href="{{ route('asientos.create') }}" class="btn btn-primary btn-sm mt-2">
                                    + Registrar el primer asiento
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Paginación --}}
        @if($asientos->hasPages())
        <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center px-4 py-3">
            <small class="text-muted">
                Mostrando {{ $asientos->firstItem() }}–{{ $asientos->lastItem() }} de {{ $asientos->total() }} asientos
            </small>
            {{ $asientos->links() }}
        </div>
        @endif
    </div>

</div>

<style>
@media print {
    .btn, nav, footer, .card-footer { display: none !important; }
    body { font-size: 11px; }
}
</style>
@endsection