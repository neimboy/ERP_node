@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-4">

    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <span class="text-uppercase text-muted small fw-bold">Módulo de Contabilidad</span>
            <h2 class="h3 mb-0 fw-bold">📋 Resumen Gerencial</h2>
            <p class="text-muted mb-0">Dashboard financiero integrado – KPIs para la toma de decisiones</p>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-outline-primary btn-sm me-2">🖨️ Imprimir</button>
            <a href="{{ route('asientos.index') }}" class="btn btn-sm btn-secondary">Volver</a>
        </div>
    </div>

    {{-- KPIs principales --}}
    <div class="row g-3 mb-4">
        @foreach($kpis as $kpi)
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">{{ $kpi['label'] }}</div>
                        <div class="fs-5 fw-bold font-monospace mt-1">{{ $kpi['valor'] }}</div>
                    </div>
                    <div class="fs-2">{{ $kpi['estado'] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Panel de datos cruzados del ERP --}}
    <div class="row g-3">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="display-6">💰</div>
                <div class="text-muted small text-uppercase fw-bold mt-2">Ventas Cobradas</div>
                <div class="fs-4 fw-bold text-success font-monospace">S/. {{ number_format($totalVentas, 2) }}</div>
                <div class="text-muted small">{{ $totalFacturas }} facturas emitidas</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="display-6">🏗️</div>
                <div class="text-muted small text-uppercase fw-bold mt-2">Proyectos Activos</div>
                <div class="fs-4 fw-bold text-primary">{{ $proyectosActivos }}</div>
                <div class="text-muted small">en ejecución actualmente</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="display-6">📦</div>
                <div class="text-muted small text-uppercase fw-bold mt-2">Valor Inventario</div>
                <div class="fs-4 fw-bold text-warning font-monospace">S/. {{ number_format($valorInventario, 2) }}</div>
                <div class="text-muted small">al costo de compra</div>
            </div>
        </div>

        {{-- Ecuación contable --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header fw-bold bg-dark text-white">⚖️ Ecuación Contable: Activo = Pasivo + Patrimonio</div>
                <div class="card-body">
                    <div class="row text-center g-3">
                        <div class="col-md-4">
                            <div class="p-3 bg-success bg-opacity-10 rounded">
                                <div class="text-muted small fw-bold text-uppercase">Total Activo</div>
                                <div class="fs-3 fw-bold text-success font-monospace">S/. {{ number_format($totalActivos, 2) }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-danger bg-opacity-10 rounded">
                                <div class="text-muted small fw-bold text-uppercase">Total Pasivo</div>
                                <div class="fs-3 fw-bold text-danger font-monospace">S/. {{ number_format($totalPasivos, 2) }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-info bg-opacity-10 rounded">
                                <div class="text-muted small fw-bold text-uppercase">Total Patrimonio</div>
                                <div class="fs-3 fw-bold text-info font-monospace">S/. {{ number_format($totalPatrimonio, 2) }}</div>
                            </div>
                        </div>
                    </div>
                    @php $cuadre = abs($totalActivos - ($totalPasivos + $totalPatrimonio)); @endphp
                    <div class="text-center mt-3">
                        @if($cuadre < 1)
                            <span class="badge bg-success fs-6 px-4 py-2">✅ Balance cuadrado correctamente</span>
                        @else
                            <span class="badge bg-danger fs-6 px-4 py-2">⚠️ Diferencia de S/. {{ number_format($cuadre, 2) }} — revisar asientos</span>
                        @endif
                    </div>
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