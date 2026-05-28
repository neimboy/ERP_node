@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 600px;">
    <div class="card border-0 shadow">
        <div class="card-header bg-warning text-dark fw-bold">
            ✏️ Editar Cuenta Contable — {{ $cuenta->Codigo }}
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                </div>
            @endif

            <form action="{{ route('contabilidad.plan_cuentas.update', $cuenta->Id_Cuenta) }}" method="POST">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Código PCGE</label>
                    <input type="text" name="Codigo" class="form-control font-monospace"
                           value="{{ old('Codigo', $cuenta->Codigo) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre de la Cuenta</label>
                    <input type="text" name="Nombre_Cuenta" class="form-control"
                           value="{{ old('Nombre_Cuenta', $cuenta->Nombre_Cuenta) }}" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Tipo</label>
                    <select name="Tipo" class="form-select" required>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo }}" {{ old('Tipo', $cuenta->Tipo) == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning fw-bold">Guardar cambios</button>
                    <a href="{{ route('contabilidad.plan_cuentas') }}" class="btn btn-light border">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection