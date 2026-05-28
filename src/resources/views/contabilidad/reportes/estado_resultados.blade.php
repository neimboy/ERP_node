@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-4">

    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <span class="text-uppercase text-muted small fw-bold">Estados Financieros</span>
            <h2 class="h3 mb-0 fw-bold">📈 Estado de Resultados</h2>
            <p class="text-muted mb-0">Ganancias y Pérdidas acumuladas del período | Soles (S/.)</p>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-outline-primary btn-sm me-2">🖨️ Imprimir</button>
            <a href="{{ route('contabilidad.estado_resultados_semestral') }}" class="btn btn-outline-info btn-sm me-2">📊 Ver Semestral</a>
            <a href="{{ route('asientos.index') }}" class="btn btn-sm btn-secondary">Volver</a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">

                    {{-- INGRESOS --}}
                    <div class="p-4 border-bottom">
                        <h6 class="text-uppercase text-muted fw-bold mb-3">💰 Ingresos</h6>
                        @forelse($detalleIngresos as $ing)
                        <div class="d-flex justify-content-between py-1">
                            <span><span class="text-muted font-monospace small me-2">{{ $ing['codigo'] }}</span>{{ $ing['nombre'] }}</span>
                            <span class="font-monospace">S/. {{ number_format($ing['monto'], 2) }}</span>
                        </div>
                        @empty
                        <p class="text-muted">Sin ingresos registrados.</p>
                        @endforelse
                    </div>
                    <div class="d-flex justify-content-between px-4 py-3 bg-success bg-opacity-10 fw-bold border-bottom">
                        <span class="text-success text-uppercase">Total Ingresos</span>
                        <span class="font-monospace text-success fs-5">S/. {{ number_format($ingresos, 2) }}</span>
                    </div>

                    {{-- GASTOS / COSTOS --}}
                    <div class="p-4 border-bottom">
                        <h6 class="text-uppercase text-muted fw-bold mb-3">💸 Costos y Gastos</h6>
                        @forelse($detalleGastos as $gas)
                        <div class="d-flex justify-content-between py-1">
                            <span><span class="text-muted font-monospace small me-2">{{ $gas['codigo'] }}</span>{{ $gas['nombre'] }}</span>
                            <span class="font-monospace text-danger">(S/. {{ number_format($gas['monto'], 2) }})</span>
                        </div>
                        @empty
                        <p class="text-muted">Sin gastos registrados.</p>
                        @endforelse
                    </div>
                    <div class="d-flex justify-content-between px-4 py-3 bg-danger bg-opacity-10 fw-bold border-bottom">
                        <span class="text-danger text-uppercase">Total Costos y Gastos</span>
                        <span class="font-monospace text-danger fs-5">(S/. {{ number_format($gastos, 2) }})</span>
                    </div>

                    {{-- RESULTADO --}}
                    <div class="d-flex justify-content-between px-4 py-4 {{ $utilidadNeta >= 0 ? 'bg-success' : 'bg-danger' }} text-white fw-bold">
                        <span class="text-uppercase fs-5">{{ $utilidadNeta >= 0 ? '✅ Utilidad Neta' : '🔴 Pérdida Neta' }}</span>
                        <span class="font-monospace fs-4">S/. {{ number_format(abs($utilidadNeta), 2) }}</span>
                    </div>

                    @if($ingresos > 0)
                    <div class="d-flex justify-content-between px-4 py-2 bg-light border-top text-muted">
                        <small>Margen neto</small>
                        <small class="font-monospace fw-bold">{{ number_format($utilidadNeta / $ingresos * 100, 1) }}%</small>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, nav, footer { display: none !important; }
    body { font-size: 12px; }
}
</style>
@endsection