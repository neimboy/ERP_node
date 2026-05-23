@csrf

<div class="mb-3">
    <label class="form-label">Cliente</label>
    <select name="cliente_id" class="form-select" required>
        <option value="">-- Seleccione cliente --</option>
        @foreach($clientes as $c)
            <option value="{{ $c->Id_Cliente }}" {{ (old('cliente_id', $cotizacion->cliente_id ?? '') == $c->Id_Cliente) ? 'selected' : '' }}>{{ $c->Nombre }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Oportunidad (opcional)</label>
    <select name="oportunidad_id" class="form-select">
        <option value="">-- Ninguna --</option>
        @foreach($oportunidades as $o)
            <option value="{{ $o->Id_Oportunidad }}" {{ (old('oportunidad_id', $cotizacion->oportunidad_id ?? '') == $o->Id_Oportunidad) ? 'selected' : '' }}>{{ $o->Titulo }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Fecha</label>
    <input type="date" name="fecha" class="form-control" value="{{ old('fecha', isset($cotizacion) ? $cotizacion->fecha->format('Y-m-d') : date('Y-m-d')) }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Estado</label>
    <select name="estado" class="form-select">
        @foreach(['BORRADOR','ENVIADA','ACEPTADA','RECHAZADA'] as $e)
            <option value="{{ $e }}" {{ (old('estado', $cotizacion->estado ?? 'BORRADOR') == $e) ? 'selected' : '' }}>{{ $e }}</option>
        @endforeach
    </select>
</div>

<h5>Items</h5>
<table class="table" id="items-table">
    <thead>
        <tr>
            <th>Producto</th>
            <th style="width:120px">Cantidad</th>
            <th style="width:140px">Precio</th>
            <th style="width:140px">Subtotal</th>
            <th style="width:80px"></th>
        </tr>
    </thead>
    <tbody>
        {{-- Existing items or old inputs --}}
        @php $index = 0; @endphp
        @if(old('items'))
            @foreach(old('items') as $it)
                <tr data-index="{{ $index }}">
                    <td>
                        <select name="items[{{ $index }}][producto_id]" class="form-select">
                            <option value="">-- Seleccione --</option>
                            @foreach($productos as $p)
                                <option value="{{ $p->Id_Producto }}" {{ ($it['producto_id'] ?? '') == $p->Id_Producto ? 'selected' : '' }}>{{ $p->Nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" step="0.01" min="0" name="items[{{ $index }}][cantidad]" class="form-control cantidad" value="{{ $it['cantidad'] }}"></td>
                    <td><input type="number" step="0.01" min="0" name="items[{{ $index }}][precio]" class="form-control precio" value="{{ $it['precio'] }}"></td>
                    <td><input type="text" name="items[{{ $index }}][subtotal]" class="form-control subtotal" readonly value="{{ $it['cantidad'] * $it['precio'] }}"></td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-item">-</button></td>
                </tr>
                @php $index++; @endphp
            @endforeach
        @elseif(isset($cotizacion))
            @foreach($cotizacion->detalles as $it)
                <tr data-index="{{ $index }}">
                    <td>
                        <select name="items[{{ $index }}][producto_id]" class="form-select">
                            <option value="">-- Seleccione --</option>
                            @foreach($productos as $p)
                                <option value="{{ $p->Id_Producto }}" {{ ($it->producto_id ?? '') == $p->Id_Producto ? 'selected' : '' }}>{{ $p->Nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" step="0.01" min="0" name="items[{{ $index }}][cantidad]" class="form-control cantidad" value="{{ $it->cantidad }}"></td>
                    <td><input type="number" step="0.01" min="0" name="items[{{ $index }}][precio]" class="form-control precio" value="{{ $it->precio }}"></td>
                    <td><input type="text" name="items[{{ $index }}][subtotal]" class="form-control subtotal" readonly value="{{ $it->subtotal }}"></td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-item">-</button></td>
                </tr>
                @php $index++; @endphp
            @endforeach
        @else
            <tr data-index="0">
                <td>
                    <select name="items[0][producto_id]" class="form-select">
                        <option value="">-- Seleccione --</option>
                        @foreach($productos as $p)
                            <option value="{{ $p->Id_Producto }}">{{ $p->Nombre }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" step="0.01" min="0" name="items[0][cantidad]" class="form-control cantidad" value="1"></td>
                <td><input type="number" step="0.01" min="0" name="items[0][precio]" class="form-control precio" value="0"></td>
                <td><input type="text" name="items[0][subtotal]" class="form-control subtotal" readonly value="0"></td>
                <td><button type="button" class="btn btn-sm btn-danger remove-item">-</button></td>
            </tr>
            @php $index = 1; @endphp
        @endif
    </tbody>
</table>

<div class="mb-3">
    <button type="button" id="add-item" class="btn btn-secondary">Agregar producto</button>
</div>

<div class="row">
    <div class="col-md-4">
        <label class="form-label">Impuesto</label>
        <input type="number" step="0.01" name="impuesto" class="form-control" value="{{ old('impuesto', $cotizacion->impuesto ?? 0) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Subtotal</label>
        <input type="text" class="form-control" id="summary-subtotal" readonly value="{{ old('subtotal', $cotizacion->subtotal ?? 0) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Total</label>
        <input type="text" class="form-control" id="summary-total" readonly value="{{ old('total', $cotizacion->total ?? 0) }}">
    </div>
</div>

<script>
document.addEventListener('click', function(e){
    if (e.target && e.target.classList.contains('remove-item')){
        const row = e.target.closest('tr');
        row.remove();
        recalc();
    }
});

document.getElementById('add-item').addEventListener('click', function(){
    const tbody = document.querySelector('#items-table tbody');
    const index = tbody.querySelectorAll('tr').length;
    const row = document.createElement('tr');
    row.setAttribute('data-index', index);
    row.innerHTML = `
        <td>
            <select name="items[${index}][producto_id]" class="form-select">
                <option value="">-- Seleccione --</option>
                @foreach($productos as $p)
                    <option value="{{ $p->Id_Producto }}">{{ addslashes($p->Nombre) }}</option>
                @endforeach
            </select>
        </td>
        <td><input type="number" step="0.01" min="0" name="items[${index}][cantidad]" class="form-control cantidad" value="1"></td>
        <td><input type="number" step="0.01" min="0" name="items[${index}][precio]" class="form-control precio" value="0"></td>
        <td><input type="text" name="items[${index}][subtotal]" class="form-control subtotal" readonly value="0"></td>
        <td><button type="button" class="btn btn-sm btn-danger remove-item">-</button></td>
    `;
    tbody.appendChild(row);
    attachListeners(row);
});

function attachListeners(row){
    const inputs = row.querySelectorAll('.cantidad, .precio');
    inputs.forEach(i => i.addEventListener('input', function(){
        const r = this.closest('tr');
        const qty = parseFloat(r.querySelector('.cantidad').value) || 0;
        const price = parseFloat(r.querySelector('.precio').value) || 0;
        r.querySelector('.subtotal').value = (qty * price).toFixed(2);
        recalc();
    }));
}

function recalc(){
    let sum = 0;
    document.querySelectorAll('#items-table tbody tr').forEach(r => {
        const val = parseFloat(r.querySelector('.subtotal')?.value || 0) || 0;
        sum += val;
    });
    document.getElementById('summary-subtotal').value = sum.toFixed(2);
    const tax = parseFloat(document.querySelector('[name="impuesto"]').value || 0) || 0;
    document.getElementById('summary-total').value = (sum + tax).toFixed(2);
}

// Attach to existing rows
document.querySelectorAll('#items-table tbody tr').forEach(attachListeners);
document.querySelector('[name="impuesto"]').addEventListener('input', recalc);
recalc();
</script>
