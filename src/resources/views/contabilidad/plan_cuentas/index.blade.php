@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-4">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <span class="text-uppercase text-muted small fw-bold">Módulo de Contabilidad</span>
            <h2 class="h3 mb-0 fw-bold">📒 Plan de Cuentas (PCGE)</h2>
            <p class="text-muted mb-0">Catálogo oficial de cuentas contables del sistema</p>
        </div>
        <div>
            <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#formNuevaCuenta">
                + Nueva Cuenta
            </button>
            <a href="{{ route('asientos.index') }}" class="btn btn-sm btn-secondary ms-2">Volver</a>
        </div>
    </div>

    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    {{-- Formulario nueva cuenta (colapsable) --}}
    <div class="collapse mb-4" id="formNuevaCuenta">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white fw-bold">Registrar Nueva Cuenta Contable</div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('contabilidad.plan_cuentas.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Código PCGE <span class="text-danger">*</span></label>
                            <input type="text" name="Codigo" class="form-control font-monospace"
                                   placeholder="Ej: 10, 104, 401..." value="{{ old('Codigo') }}" required>
                            <div class="form-text">Código numérico del PCGE peruano</div>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Nombre de la Cuenta <span class="text-danger">*</span></label>
                            <input type="text" name="Nombre_Cuenta" class="form-control"
                                   placeholder="Ej: Caja y Bancos" value="{{ old('Nombre_Cuenta') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Tipo <span class="text-danger">*</span></label>
                            <select name="Tipo" class="form-select" required>
                                <option value="">— Seleccionar —</option>
                                @foreach(['Activo','Activo (Contra)','Pasivo','Patrimonio','Ingreso','Gasto','Costo'] as $tipo)
                                    <option value="{{ $tipo }}" {{ old('Tipo') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-success w-100">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabla de cuentas agrupada por tipo --}}
    @foreach([
        'Activo'         => ['color' => 'success',   'icono' => '🏦'],
        'Activo (Contra)'=> ['color' => 'warning',   'icono' => '⬇️'],
        'Pasivo'         => ['color' => 'danger',    'icono' => '💳'],
        'Patrimonio'     => ['color' => 'info',      'icono' => '🏛️'],
        'Ingreso'        => ['color' => 'primary',   'icono' => '💰'],
        'Gasto'          => ['color' => 'secondary', 'icono' => '💸'],
        'Costo'          => ['color' => 'dark',      'icono' => '📦'],
    ] as $tipo => $meta)
        @if(isset($agrupadas[$tipo]) && $agrupadas[$tipo]->count() > 0)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-{{ $meta['color'] }} {{ in_array($meta['color'],['warning','info']) ? 'text-dark' : 'text-white' }} py-2 px-4 d-flex justify-content-between">
                <span class="fw-bold">{{ $meta['icono'] }} {{ $tipo }}</span>
                <span class="badge bg-white text-dark">{{ $agrupadas[$tipo]->count() }} cuentas</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width:15%">Código</th>
                            <th>Nombre de la Cuenta</th>
                            <th style="width:12%" class="text-center">Movimientos</th>
                            <th style="width:10%" class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agrupadas[$tipo]->sortBy('Codigo') as $cuenta)
                        <tr>
                            <td class="ps-4 font-monospace fw-bold text-{{ $meta['color'] }}">{{ $cuenta->Codigo }}</td>
                            <td>{{ $cuenta->Nombre_Cuenta }}</td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">{{ $cuenta->detalles_count ?? $cuenta->detalles()->count() }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('contabilidad.plan_cuentas.edit', $cuenta->Id_Cuenta) }}" class="btn btn-xs btn-outline-secondary btn-sm py-0 px-2">Editar</a>
                                <form action="{{ route('contabilidad.plan_cuentas.destroy', $cuenta->Id_Cuenta) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar la cuenta {{ $cuenta->Codigo }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    @endforeach

    @if($cuentas->isEmpty())
        <div class="text-center py-5 text-muted">
            <p class="fs-4">📋</p>
            <p>No hay cuentas registradas. Empieza añadiendo las cuentas del PCGE.</p>
        </div>
    @endif

</div>
@endsection