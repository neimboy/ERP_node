@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-4">

    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <span class="text-uppercase text-muted small fw-bold">Estados Financieros</span>
            <h2 class="h3 mb-0 fw-bold">📊 Estado de Resultados Semestral</h2>
            <p class="text-muted mb-0">Evolución mensual de ingresos, costos y utilidad | Soles (S/.)</p>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-outline-primary btn-sm me-2">🖨️ Imprimir</button>
            <a href="{{ route('asientos.index') }}" class="btn btn-sm btn-secondary">Volver</a>
        </div>
    </div>

    @if(empty($meses))
        <div class="text-center py-5 text-muted">
            <p class="fs-2">📭</p>
            <p>No hay períodos con movimientos registrados.</p>
        </div>
    @else

    {{-- Tabla comparativa mensual --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="min-width: 900px;">
                    <thead class="table-dark text-center text-uppercase" style="font-size: 0.8rem;">
                        <tr>
                            <th class="text-start ps-4 py-3">Período</th>
                            <th class="py-3">Ingresos</th>
                            <th class="py-3">Costos</th>
                            <th class="py-3">Gastos Oper.</th>
                            <th class="py-3">Util. Bruta</th>
                            <th class="py-3">Margen Bruto</th>
                            <th class="py-3">Util. Operativa</th>
                            <th class="py-3">Margen Oper.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($meses as $fila)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $fila['label'] }}</td>
                            <td class="text-end pe-3 font-monospace text-success">{{ $fila['ingresos'] > 0 ? 'S/. ' . number_format($fila['ingresos'], 2) : '—' }}</td>
                            <td class="text-end pe-3 font-monospace text-danger">{{ $fila['costos'] > 0 ? '(S/. ' . number_format($fila['costos'], 2) . ')' : '—' }}</td>
                            <td class="text-end pe-3 font-monospace text-warning">{{ $fila['gastos'] > 0 ? '(S/. ' . number_format($fila['gastos'], 2) . ')' : '—' }}</td>
                            <td class="text-end pe-3 font-monospace fw-bold {{ $fila['utilidad_bruta'] >= 0 ? 'text-success' : 'text-danger' }}">
                                S/. {{ number_format($fila['utilidad_bruta'], 2) }}
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill {{ $fila['margen_bruto'] >= 40 ? 'bg-success' : ($fila['margen_bruto'] >= 20 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                    {{ $fila['margen_bruto'] }}%
                                </span>
                            </td>
                            <td class="text-end pe-3 font-monospace fw-bold {{ $fila['utilidad_operativa'] >= 0 ? 'text-primary' : 'text-danger' }}">
                                S/. {{ number_format($fila['utilidad_operativa'], 2) }}
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill {{ $fila['margen_operativo'] >= 15 ? 'bg-success' : ($fila['margen_operativo'] >= 5 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                    {{ $fila['margen_operativo'] }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-dark fw-bold text-end border-top-2" style="font-size: 0.9rem;">
                        <tr>
                            <td class="text-start ps-4 py-3">TOTAL PERÍODO</td>
                            <td class="pe-3 font-monospace text-success">S/. {{ number_format($totalIngresos, 2) }}</td>
                            <td class="pe-3 font-monospace">(S/. {{ number_format($totalCostos, 2) }})</td>
                            <td class="pe-3 font-monospace">(S/. {{ number_format($totalGastos, 2) }})</td>
                            <td class="pe-3 font-monospace text-success">S/. {{ number_format($totalUtilidadBruta, 2) }}</td>
                            <td class="text-center"><span class="badge bg-success">{{ $margenBrutoTotal }}%</span></td>
                            <td class="pe-3 font-monospace text-primary">S/. {{ number_format($totalUtilidadOperativa, 2) }}</td>
                            <td class="text-center"><span class="badge bg-primary">{{ $margenOperativoTotal }}%</span></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Tarjetas resumen --}}
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card border-0 bg-success bg-opacity-10 text-center p-3">
                <div class="text-muted small text-uppercase fw-bold mb-1">Total Ingresos</div>
                <div class="fs-4 fw-bold text-success font-monospace">S/. {{ number_format($totalIngresos, 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-danger bg-opacity-10 text-center p-3">
                <div class="text-muted small text-uppercase fw-bold mb-1">Total Costos + Gastos</div>
                <div class="fs-4 fw-bold text-danger font-monospace">S/. {{ number_format($totalCostos + $totalGastos, 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-primary bg-opacity-10 text-center p-3">
                <div class="text-muted small text-uppercase fw-bold mb-1">Utilidad Operativa</div>
                <div class="fs-4 fw-bold text-primary font-monospace">S/. {{ number_format($totalUtilidadOperativa, 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-info bg-opacity-10 text-center p-3">
                <div class="text-muted small text-uppercase fw-bold mb-1">Margen Operativo</div>
                <div class="fs-4 fw-bold text-info font-monospace">{{ $margenOperativoTotal }}%</div>
            </div>
        </div>
    </div>

    @endif

</div>

<style>
@media print {
    .btn, nav, footer { display: none !important; }
    body { font-size: 11px; }
    .table { border-collapse: collapse; }
}
</style>
@endsection