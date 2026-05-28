@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <span class="text-uppercase text-muted small fw-bold">Estados Financieros</span>
            <h2 class="h3 mb-0 text-gray-800 fw-bold">🏦 Balance General</h2>
            <p class="text-muted mb-0">Ecuación Contable: ACTIVO = PASIVO + PATRIMONIO | Expresado en Soles (S/.)</p>
        </div>
        <div>
            <button onclick="window.print();" class="btn btn-outline-primary btn-sm me-2">
                <i class="bi bi-printer"></i> Imprimir Balance
            </button>
            <a href="{{ route('asientos.index') }}" class="btn btn-sm btn-secondary">Volver al ERP</a>
        </div>
    </div>

    @if(abs($totalActivo - $totalPasivoYPatrimonio) > 0.05)
        <div class="alert alert-warning d-flex align-items-center shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
            <div>
                <strong>Aviso de Descuadre Contable:</strong> La suma total del Activo no coincide exactamente con el Pasivo + Patrimonio por una diferencia de S/. {{ number_format(abs($totalActivo - $totalPasivoYPatrimonio), 2) }}. Por favor revise los asientos diarios de regularización.
            </div>
        </div>
    @else
        <div class="alert alert-success d-flex align-items-center shadow-sm py-2" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>¡Balance cuadrado correctamente bajo el principio de partida doble!</div>
        </div>
    @endif

    <div class="row">
        
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="card-title mb-0 fw-bold text-uppercase fs-6">Estructura del Activo</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Cuenta / Concepto</th>
                                <th class="text-end pe-4" style="width: 35%;">Monto Actual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activos as $activo)
                                <tr>
                                    <td class="ps-4">
                                        <span class="text-muted small fw-bold me-2">{{ $activo['codigo'] }}</span>
                                        {{ $activo['nombre'] }}
                                    </td>
                                    <td class="text-end pe-4 font-monospace @if($activo['monto'] < 0) text-danger @endif">
                                        S/. {{ number_format($activo['monto'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-4 text-muted">No hay registros de cuentas de Activos.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-success bg-opacity-10 border-top-0 py-3 px-4 d-flex justify-content-between align-items-center fw-bold">
                    <span class="text-uppercase text-success">Total Activo:</span>
                    <span class="font-monospace fs-5 text-success">S/. {{ number_format($totalActivo, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 d-flex flex-column h-100">
                
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="card-title mb-0 fw-bold text-uppercase fs-6">Estructura del Pasivo</h5>
                </div>
                <div class="p-0 flex-grow-1">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Cuenta / Concepto</th>
                                <th class="text-end pe-4" style="width: 35%;">Monto Actual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pasivos as $pasivo)
                                <tr>
                                    <td class="ps-4">
                                        <span class="text-muted small fw-bold me-2">{{ $pasivo['codigo'] }}</span>
                                        {{ $pasivo['nombre'] }}
                                    </td>
                                    <td class="text-end pe-4 font-monospace">
                                        S/. {{ number_format($pasivo['monto'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-3 text-muted">No hay deudas ni obligaciones corrientes registradas.</td>
                                </tr>
                            @endforelse
                            
                            <tr class="table-light fw-semibold">
                                <td class="ps-4 text-uppercase text-muted small">Subtotal Pasivos</td>
                                <td class="text-end pe-4 font-monospace">S/. {{ number_format($totalPasivo, 2) }}</td>
                            </tr>

                            <tr class="table-dark">
                                <td colspan="2" class="ps-4 py-2 fw-bold text-uppercase fs-7 text-white">Estructura del Patrimonio</td>
                            </tr>
                            @forelse($patrimonio as $pat)
                                <tr>
                                    <td class="ps-4">
                                        <span class="text-muted small fw-bold me-2">{{ $pat['codigo'] }}</span>
                                        {{ $pat['nombre'] }}
                                    </td>
                                    <td class="text-end pe-4 font-monospace">
                                        S/. {{ number_format($pat['monto'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-3 text-muted">No se registran cuentas patrimoniales.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-dark bg-opacity-10 border-top-0 py-3 px-4 d-flex justify-content-between align-items-center fw-bold">
                    <span class="text-uppercase text-dark">Total Pasivo y Patrimonio:</span>
                    <span class="font-monospace fs-5 text-dark">S/. {{ number_format($totalPasivoYPatrimonio, 2) }}</span>
                </div>

            </div>
        </div>

    </div>
</div>

<style>
    @media print {
        .btn, .alert, footer, nav, sidebar, .navbar { display: none !important; }
        body { background-color: #fff; font-size: 11px; }
        .col-lg-6 { width: 50% !important; float: left; }
        .card { box-shadow: none !important; }
    }
    .fs-7 { font-size: 0.85rem; }
</style>
@endsection