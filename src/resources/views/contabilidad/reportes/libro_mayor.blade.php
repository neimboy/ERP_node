@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <span class="text-uppercase text-muted small fw-bold">Módulo de Contabilidad</span>
            <h2 class="h3 mb-0 text-gray-800 fw-bold">📗 Libro Mayor</h2>
            <p class="text-muted mb-0">Acumulado de movimientos y saldos por cuenta contable (PCGE)</p>
        </div>
        <div>
            <button onclick="window.print();" class="btn btn-outline-primary btn-sm me-2">
                <i class="bi bi-printer"></i> Imprimir Reporte
            </button>
            <a href="{{ route('asientos.index') }}" class="btn btn-sm btn-secondary">Volver al ERP</a>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="min-width: 900px;">
                    <thead class="table-dark text-uppercase fs-7 text-center">
                        <tr>
                            <th class="text-start py-3" style="width: 15%;">Código</th>
                            <th class="text-start py-3" style="width: 30%;">Cuenta Contable</th>
                            <th class="py-3" style="width: 12%;">Tipo</th>
                            <th class="py-3" style="width: 11%;">Total Debe</th>
                            <th class="py-3" style="width: 11%;">Total Haber</th>
                            <th class="py-3" style="width: 11%;">Saldo Deudor</th>
                            <th class="py-3" style="width: 11%;">Saldo Acreedor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cuentasMayor as $cuenta)
                            <tr class="@if($cuenta['total_debe'] == 0 && $cuenta['total_haber'] == 0) text-muted table-light @endif">
                                <td class="text-start ps-4 fw-bold text-primary">
                                    {{ $cuenta['codigo'] }}
                                </td>
                                <td class="text-start fw-semibold">
                                    {{ $cuenta['nombre'] }}
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-opacity-10 
                                        @if($cuenta['tipo'] === 'Activo') bg-success text-success 
                                        @elseif($cuenta['tipo'] === 'Pasivo') bg-danger text-danger 
                                        @elseif($cuenta['tipo'] === 'Patrimonio') bg-warning text-dark 
                                        @elseif($cuenta['tipo'] === 'Ingreso') bg-info text-dark 
                                        @else bg-secondary text-secondary @endif">
                                        {{ $cuenta['tipo'] }}
                                    </span>
                                </td>
                                <td class="text-end pe-3 font-monospace">
                                    {{ $cuenta['total_debe'] > 0 ? 'S/. ' . number_format($cuenta['total_debe'], 2) : '-' }}
                                </td>
                                <td class="text-end pe-3 font-monospace">
                                    {{ $cuenta['total_haber'] > 0 ? 'S/. ' . number_format($cuenta['total_haber'], 2) : '-' }}
                                </td>
                                <td class="text-end pe-3 font-monospace fw-bold text-success">
                                    {{ $cuenta['saldo_deudor'] > 0 ? 'S/. ' . number_format($cuenta['saldo_deudor'], 2) : '-' }}
                                </td>
                                <td class="text-end pe-3 font-monospace fw-bold text-danger">
                                    {{ $cuenta['saldo_acreedor'] > 0 ? 'S/. ' . number_format($cuenta['saldo_acreedor'], 2) : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-exclamation-circle fs-2 d-block mb-2"></i>
                                    No se encontraron cuentas contables con movimientos en este período.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light border-top-2 fw-bold text-end">
                        <tr>
                            <td colspan="3" class="text-end py-3">Totales Generales del Mayor:</td>
                            <td class="pe-3 font-monospace text-dark">S/. {{ number_format($cuentasMayor->sum('total_debe'), 2) }}</td>
                            <td class="pe-3 font-monospace text-dark">S/. {{ number_format($cuentasMayor->sum('total_haber'), 2) }}</td>
                            <td class="pe-3 font-monospace text-success">S/. {{ number_format($cuentasMayor->sum('saldo_deudor'), 2) }}</td>
                            <td class="pe-3 font-monospace text-danger">S/. {{ number_format($cuentasMayor->sum('saldo_acreedor'), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos de impresión optimizados */
    @media print {
        .btn, footer, nav, sidebar, .navbar { display: none !important; }
        body { background-color: #fff; color: #000; font-size: 12px; }
        .card { shadow: none !important; border: 0 !important; }
        .table { width: 100% !important; border-collapse: collapse; }
        th { background-color: #333 !important; color: #fff !important; }
    }
    .fs-7 { font-size: 0.85rem; }
</style>
@endsection