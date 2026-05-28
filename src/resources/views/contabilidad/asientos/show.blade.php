@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-4">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <span class="text-uppercase text-muted small fw-bold">Contabilidad / Asientos</span>
            <h2 class="h3 mb-0 fw-bold">
                📄 Asiento #{{ str_pad($asiento->Id_Asiento, 4, '0', STR_PAD_LEFT) }}
            </h2>
            <p class="text-muted mb-0">{{ $asiento->Glosa }}</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-primary btn-sm">🖨️ Imprimir</button>
            <a href="{{ route('asientos.index') }}" class="btn btn-sm btn-secondary">← Volver</a>
        </div>
    </div>

    {{-- Estado del asiento --}}
    @php $cuadra = abs($totalDebe - $totalHaber) <= 0.01; @endphp
    @if($cuadra)
        <div class="alert alert-success d-flex align-items-center py-2 shadow-sm mb-4">
            <span class="me-2">✅</span>
            <span>Asiento cuadrado correctamente — Partida Doble verificada.</span>
        </div>
    @else
        <div class="alert alert-danger d-flex align-items-center py-2 shadow-sm mb-4">
            <span class="me-2">⚠️</span>
            <span>
                Asiento <strong>descuadrado</strong>. Diferencia:
                S/. {{ number_format(abs($totalDebe - $totalHaber), 2) }}.
                Debe = S/. {{ number_format($totalDebe, 2) }} |
                Haber = S/. {{ number_format($totalHaber, 2) }}
            </span>
        </div>
    @endif

    <div class="row g-4">

        {{-- Panel izquierdo: datos del asiento --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white fw-bold py-3">
                    📋 Datos del Asiento
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-5 text-muted">N° Asiento</dt>
                        <dd class="col-7 font-monospace fw-bold text-primary">
                            #{{ str_pad($asiento->Id_Asiento, 4, '0', STR_PAD_LEFT) }}
                        </dd>

                        <dt class="col-5 text-muted">Fecha</dt>
                        <dd class="col-7 font-monospace">
                            {{ \Carbon\Carbon::parse($asiento->Fecha)->format('d/m/Y') }}
                        </dd>

                        <dt class="col-5 text-muted">Período</dt>
                        <dd class="col-7">
                            @if($asiento->periodo)
                                <span class="badge bg-info text-dark">
                                    {{ $asiento->periodo->label ?? ($asiento->periodo->Año . ' – Mes ' . $asiento->periodo->Mes) }}
                                </span>
                                <br>
                                <small class="text-muted">Estado: {{ $asiento->periodo->Estado ?? 'N/D' }}</small>
                            @else
                                <span class="text-muted">Sin período asignado</span>
                            @endif
                        </dd>

                        <dt class="col-5 text-muted">Glosa</dt>
                        <dd class="col-7">{{ $asiento->Glosa }}</dd>

                        <dt class="col-5 text-muted">Registrado</dt>
                        <dd class="col-7 small text-muted">
                            {{ $asiento->created_at?->format('d/m/Y H:i') ?? '—' }}
                        </dd>
                    </dl>
                </div>

                {{-- Totales cuadro resumen --}}
                <div class="card-footer bg-light border-top-0">
                    <div class="d-flex justify-content-between py-1">
                        <span class="fw-bold text-muted small text-uppercase">Total Debe</span>
                        <span class="font-monospace fw-bold text-success">S/. {{ number_format($totalDebe, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-top">
                        <span class="fw-bold text-muted small text-uppercase">Total Haber</span>
                        <span class="font-monospace fw-bold text-danger">S/. {{ number_format($totalHaber, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-top">
                        <span class="fw-bold small text-uppercase {{ $cuadra ? 'text-success' : 'text-danger' }}">
                            {{ $cuadra ? '✅ Cuadrado' : '⚠️ Diferencia' }}
                        </span>
                        <span class="font-monospace fw-bold {{ $cuadra ? 'text-success' : 'text-danger' }}">
                            S/. {{ number_format(abs($totalDebe - $totalHaber), 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel derecho: líneas de detalle --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white fw-bold py-3 d-flex justify-content-between">
                    <span>📊 Líneas Contables ({{ $asiento->detalles->count() }} partidas)</span>
                    <span class="badge bg-light text-dark">Partida Doble</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase" style="font-size: 0.78rem;">
                            <tr>
                                <th class="ps-4 py-3" style="width: 12%;">Código</th>
                                <th class="py-3">Cuenta Contable</th>
                                <th class="py-3 text-center" style="width: 10%;">Tipo</th>
                                <th class="py-3 text-end" style="width: 16%;">Debe S/.</th>
                                <th class="py-3 text-end pe-4" style="width: 16%;">Haber S/.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($asiento->detalles as $detalle)
                            <tr>
                                <td class="ps-4 font-monospace fw-bold text-primary small">
                                    {{ $detalle->cuenta->Codigo ?? '—' }}
                                </td>
                                <td>{{ $detalle->cuenta->Nombre_Cuenta ?? 'Cuenta no encontrada' }}</td>
                                <td class="text-center">
                                    @php $tipo = $detalle->cuenta->Tipo ?? ''; @endphp
                                    <span class="badge rounded-pill
                                        @if($tipo === 'Activo') bg-success bg-opacity-75
                                        @elseif($tipo === 'Pasivo') bg-danger bg-opacity-75
                                        @elseif($tipo === 'Patrimonio') bg-warning text-dark
                                        @elseif($tipo === 'Ingreso') bg-info text-dark
                                        @elseif(in_array($tipo, ['Gasto','Costo'])) bg-secondary
                                        @else bg-light text-dark border @endif"
                                        style="font-size: 0.7rem;">
                                        {{ $tipo ?: 'N/D' }}
                                    </span>
                                </td>
                                <td class="text-end font-monospace pe-3">
                                    @if($detalle->Debe > 0)
                                        <span class="text-success fw-bold">S/. {{ number_format($detalle->Debe, 2) }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-end font-monospace pe-4">
                                    @if($detalle->Haber > 0)
                                        <span class="text-danger fw-bold">S/. {{ number_format($detalle->Haber, 2) }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Este asiento no tiene líneas de detalle.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light border-top fw-bold">
                            <tr>
                                <td colspan="3" class="text-end py-3 text-uppercase text-muted small ps-4">
                                    Totales:
                                </td>
                                <td class="text-end font-monospace text-success pe-3">
                                    S/. {{ number_format($totalDebe, 2) }}
                                </td>
                                <td class="text-end font-monospace text-danger pe-4">
                                    S/. {{ number_format($totalHaber, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Acciones --}}
            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('asientos.index') }}" class="btn btn-light border">← Volver a la lista</a>
                <a href="{{ route('asientos.create') }}" class="btn btn-primary">+ Nuevo Asiento</a>
                <form action="{{ route('asientos.destroy', $asiento->Id_Asiento) }}"
                      method="POST" class="ms-auto"
                      onsubmit="return confirm('¿Eliminar este asiento y todas sus líneas?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger">🗑 Eliminar Asiento</button>
                </form>
            </div>
        </div>

    </div>

</div>

<style>
@media print {
    .btn, .alert, nav, footer { display: none !important; }
    body { font-size: 11px; }
    .card { box-shadow: none !important; border: 1px solid #ccc !important; }
    .col-lg-4, .col-lg-8 { width: 40% !important; float: left; }
}
</style>
@endsection