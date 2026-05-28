@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Registrar Asiento Diario Contable</h4>
        </div>
        <div class="card-body">
            @if($errors->has('partida_doble'))
                <div class="alert alert-danger">{{ $errors->first('partida_doble') }}</div>
            @endif

            <form action="{{ route('asientos.store') }}" method="POST" id="asientoForm">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Período Contable</label>
                        <select name="Id_Periodo" class="form-select" required>
                            @foreach($periodos as $p)
                                <option value="{{ $p->Id_Periodo }}">{{ $p->Año }} - Mes {{ $p->Mes }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fecha de Asiento</label>
                        <input type="date" name="Fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Glosa / Descripción</label>
                        <input type="text" name="Glosa" class="form-control" placeholder="Ej: Por la provisión de la factura..." required>
                    </div>
                </div>

                <h5 class="mt-4">Detalle del Asiento (Líneas Contables)</h5>
                <table class="table table-bordered" id="detalleTable">
                    <thead class="table-light">
                        <tr>
                            <th>Cuenta Contable</th>
                            <th style="width: 20%;">Debe (S/.)</th>
                            <th style="width: 20%;">Haber (S/.)</th>
                            <th style="width: 10%;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="items[0][Id_Cuenta]" class="form-select" required>
                                    @foreach($cuentas as $c)
                                        <option value="{{ $c->Id_Cuenta }}">{{ $c->Codigo }} - {{ $c->Nombre_Cuenta }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="items[0][Debe]" class="form-control debe-input" value="0.00" step="0.01" min="0" required></td>
                            <td><input type="number" name="items[0][Haber]" class="form-control haber-input" value="0.00" step="0.01" min="0" required></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row">Eliminar</button></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="table-info font-weight-bold">
                            <td class="text-end"><strong>Totales Cuadrados:</strong></td>
                            <td><span id="txtTotalDebe">0.00</span></td>
                            <td><span id="txtTotalHaber">0.00</span></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                <button type="button" class="btn btn-secondary btn-sm mb-3" id="btnAnadirLinea">+ Añadir Línea</button>
                <div class="text-end">
                    <a href="{{ route('asientos.index') }}" class="btn btn-light border">Cancelar</a>
                    <button type="submit" class="btn btn-success">Guardar Asiento Contable</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let index = 1;
    const tbody = document.querySelector('#detalleTable tbody');
    
    document.getElementById('btnAnadirLinea').addEventListener('click', function() {
        let row = document.querySelector('#detalleTable tbody tr').cloneNode(true);
        row.querySelectorAll('select, input').forEach(input => {
            let name = input.getAttribute('name');
            input.setAttribute('name', name.replace(/\[\d+\]/, '[' + index + ']'));
            if(input.tagName === 'INPUT') input.value = "0.00";
        });
        tbody.appendChild(row);
        index++;
        calcularTotales();
    });

    tbody.addEventListener('click', function(e) {
        if(e.target.classList.contains('remove-row')) {
            if(tbody.rows.length > 1) {
                e.target.closest('tr').remove();
                calcularTotales();
            }
        }
    });

    tbody.addEventListener('input', function(e) {
        if(e.target.classList.contains('debe-input') || e.target.classList.contains('haber-input')) {
            calcularTotales();
        }
    });

    function calcularTotales() {
        let db = 0, hb = 0;
        document.querySelectorAll('.debe-input').forEach(i => db += parseFloat(i.value || 0));
        document.querySelectorAll('.haber-input').forEach(i => hb += parseFloat(i.value || 0));
        document.getElementById('txtTotalDebe').innerText = db.toFixed(2);
        document.getElementById('txtTotalHaber').innerText = hb.toFixed(2);
    }
});
</script>
@endsection