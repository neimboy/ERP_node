@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-4">

    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <span class="text-uppercase text-muted small fw-bold">Módulo de Contabilidad</span>
            <h2 class="h3 mb-0 fw-bold">🧾 Declaración Mensual de IGV</h2>
            <p class="text-muted mb-0">PDT 621 – SUNAT | Tasa: 18% | Débito Fiscal vs. Crédito Fiscal</p>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-outline-primary btn-sm me-2">🖨️ Imprimir</button>
            <a href="{{ route('asientos.index') }}" class="btn btn-sm btn-secondary">Volver</a>
        </div>
    </div>

    {{-- Alerta resultado global --}}
    @if($igvAPagar > 0)
        <div class="alert alert-warning d-flex align-items-center shadow-sm">
            <span class="fs-4 me-3">⚠️</span>
            <div>IGV neto a <strong>pagar a SUNAT: S/. {{ number_format($igvAPagar, 2) }}</strong></div>
        </div>
    @elseif($igvAPagar < 0)
        <div class="alert alert-success d-flex align-items-center shadow-sm">
            <span class="fs-4 me-3">✅</span>
            <div>Saldo a favor del contribuyente: <strong>S/. {{ number_format(abs($igvAPagar), 2) }}</strong></div>
        </div>
    @else
        <div class="alert alert-info">IGV Equilibrado – No hay monto a pagar ni saldo a favor.</div>
    @endif

    {{-- Detalle mensual --}}
    @if(!empty($filasMensuales))
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header fw-bold bg-dark text-white">Detalle Mensual</div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase text-center" style="font-size: 0.8rem;">
                    <tr>
                        <th class="text-start ps-4 py-3">Período</th>
                        <th class="py-3">IGV Ventas (Débito)</th>
                        <th class="py-3">IGV Compras (Crédito)</th>
                        <th class="py-3">IGV Neto</th>
                        <th class="py-3">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($filasMensuales as $fila)
                    <tr>
                        <td class="ps-4 fw-semibold">{{ $fila['label'] }}</td>
                        <td class="text-end pe-3 font-monospace text-primary">S/. {{ number_format($fila['igv_ventas'], 2) }}</td>
                        <td class="text-end pe-3 font-monospace text-success">S/. {{ number_format($fila['igv_compras'], 2) }}</td>
                        <td class="text-end pe-3 font-monospace fw-bold {{ $fila['igv_neto'] > 0 ? 'text-danger' : 'text-success' }}">
                            S/. {{ number_format(abs($fila['igv_neto']), 2) }}
                            {{ $fila['igv_neto'] > 0 ? '▲' : '▼' }}
                        </td>
                        <td class="text-center">
                            @if($fila['igv_neto'] > 0)
                                <span class="badge bg-danger">A Pagar</span>
                            @else
                                <span class="badge bg-success">A Favor</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-dark fw-bold text-end">
                    <tr>
                        <td class="text-start ps-4 py-3">TOTAL</td>
                        <td class="pe-3 font-monospace">S/. {{ number_format($igvVentasTotal, 2) }}</td>
                        <td class="pe-3 font-monospace">S/. {{ number_format($igvComprasTotal, 2) }}</td>
                        <td class="pe-3 font-monospace {{ $igvAPagar > 0 ? 'text-danger' : 'text-success' }}">
                            S/. {{ number_format(abs($igvAPagar), 2) }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif

    {{-- Fórmula explicativa --}}
    <div class="card border-0 bg-light shadow-sm">
        <div class="card-body py-3 px-4">
            <p class="mb-0 text-muted small">
                📌 <strong>Fórmula PDT 621:</strong> IGV Neto = IGV Ventas (Débito Fiscal) – IGV Compras (Crédito Fiscal).
                Resultado positivo → pagar a SUNAT. Resultado negativo → saldo a favor del contribuyente que se arrastra al siguiente mes.
            </p>
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