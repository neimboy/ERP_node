@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-4">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <span class="text-uppercase text-muted small fw-bold">Módulo de Contabilidad</span>
            <h2 class="h3 mb-0 fw-bold">📅 Períodos Contables</h2>
            <p class="text-muted mb-0">Gestión de períodos mensuales — abre o cierra cada mes para controlar los asientos</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#formNuevoPeriodo">
                + Nuevo Período
            </button>
            <a href="{{ route('asientos.index') }}" class="btn btn-sm btn-secondary">← Volver</a>
        </div>
    </div>

    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            ⚠️ {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show shadow-sm">
            ⚠️ {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Formulario nuevo período (colapsable) --}}
    <div class="collapse mb-4" id="formNuevoPeriodo">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white fw-bold">Crear Nuevo Período Contable</div>
            <div class="card-body">
                <form action="{{ route('contabilidad.periodos.store') }}" method="POST">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Año <span class="text-danger">*</span></label>
                            <input type="number" name="Año" class="form-control"
                                   value="{{ old('Año', date('Y')) }}"
                                   min="2000" max="2100" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Mes <span class="text-danger">*</span></label>
                            <select name="Mes" class="form-select" required>
                                <option value="">— Seleccionar —</option>
                                @foreach([1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',
                                          7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'] as $num => $nombre)
                                    <option value="{{ $num }}" {{ old('Mes') == $num ? 'selected' : '' }}>
                                        {{ str_pad($num, 2, '0', STR_PAD_LEFT) }} – {{ $nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success w-100">Crear Período</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabla de períodos --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark text-uppercase" style="font-size: 0.8rem;">
                        <tr>
                            <th class="ps-4 py-3" style="width: 8%;">#</th>
                            <th class="py-3" style="width: 20%;">Período</th>
                            <th class="py-3 text-center" style="width: 15%;">Estado</th>
                            <th class="py-3 text-center" style="width: 15%;">Asientos</th>
                            <th class="py-3 text-center pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($periodos as $periodo)
                        <tr>
                            <td class="ps-4 font-monospace text-muted">{{ $periodo->Id_Periodo }}</td>
                            <td class="fw-semibold">
                                {{ $periodo->label }}
                            </td>
                            <td class="text-center">
                                @if($periodo->Estado === 'Abierto')
                                    <span class="badge bg-success px-3 py-2">🟢 Abierto</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2">🔒 Cerrado</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border rounded-pill px-3">
                                    {{ $periodo->asientos_count }}
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Abrir/Cerrar --}}
                                    <form action="{{ route('contabilidad.periodos.toggle', $periodo->Id_Periodo) }}"
                                          method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="btn btn-sm {{ $periodo->Estado === 'Abierto' ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                            onclick="return confirm('¿{{ $periodo->Estado === 'Abierto' ? 'Cerrar' : 'Reabrir' }} el período {{ $periodo->label }}?')">
                                            {{ $periodo->Estado === 'Abierto' ? '🔒 Cerrar' : '🔓 Reabrir' }}
                                        </button>
                                    </form>

                                    {{-- Ver asientos del período --}}
                                    <a href="{{ route('contabilidad.libro_mayor') }}?Id_Periodo={{ $periodo->Id_Periodo }}"
                                       class="btn btn-sm btn-outline-info">
                                        📗 Mayor
                                    </a>

                                    {{-- Eliminar (solo si no tiene asientos) --}}
                                    @if($periodo->asientos_count === 0)
                                    <form action="{{ route('contabilidad.periodos.destroy', $periodo->Id_Periodo) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Eliminar el período {{ $periodo->label }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">✕</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <p class="fs-3 mb-2">📅</p>
                                <p class="mb-2">No hay períodos contables creados.</p>
                                <button class="btn btn-primary btn-sm"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#formNuevoPeriodo">
                                    + Crear el primer período
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Leyenda informativa --}}
    <div class="card border-0 bg-light shadow-sm mt-3">
        <div class="card-body py-3 px-4">
            <p class="mb-0 text-muted small">
                📌 <strong>¿Cómo funciona?</strong>
                Solo se pueden registrar asientos en períodos <span class="badge bg-success">Abiertos</span>.
                Al <strong>cerrar</strong> un período, ya no se pueden añadir ni modificar asientos en ese mes.
                No se puede cerrar un período con asientos descuadrados.
                Si necesitas corregir algo en un período cerrado, usa <strong>Reabrir</strong>.
            </p>
        </div>
    </div>

</div>
@endsection