@extends('layouts.app')

@section('content')

<div class="mb-6">
    <a href="{{ route('cotizaciones.index') }}" class="text-indigo-600 hover:text-indigo-800">
        <i class="fas fa-arrow-left mr-2"></i> Volver
    </a>
    <h2 class="text-3xl font-bold text-gray-800 mt-2">Nueva Cotización</h2>
</div>

@if ($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
        <h3 class="font-semibold text-red-800 mb-2">Errores:</h3>
        <ul class="list-disc list-inside text-red-700 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('cotizaciones.store') }}" method="POST" id="cotizacionForm">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="Id_Cliente" class="block text-sm font-medium text-gray-700 mb-1">
                    Cliente <span class="text-red-500">*</span>
                </label>
                <select id="Id_Cliente" name="Id_Cliente" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('Id_Cliente') border-red-500 @enderror">
                    <option value="">-- Seleccione Cliente --</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->Id_Cliente }}" {{ old('Id_Cliente') == $cliente->Id_Cliente ? 'selected' : '' }}>
                            {{ $cliente->Nombre }}
                        </option>
                    @endforeach
                </select>
                @error('Id_Cliente')<span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
            </div>
        </div>

        <!-- Productos -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-semibold text-gray-800">Productos</h3>
                <button type="button" id="addLineBtn" class="px-3 py-1 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
                    + Agregar
                </button>
            </div>

            <div id="lineasContainer" class="space-y-4">
                <div class="lineaItem grid grid-cols-1 md:grid-cols-5 gap-3 p-3 bg-gray-50 rounded-lg border">
                    <div>
                        <label class="text-xs font-medium text-gray-600">Producto</label>
                        <select name="lineas[0][Id_Producto]" required class="w-full px-2 py-1 text-sm border rounded productoSelect">
                            <option value="">Seleccionar...</option>
                            @foreach($productos as $prod)
                                <option value="{{ $prod->Id_Producto }}" data-precio="{{ $prod->Precio_Venta ?? 0 }}">
                                    {{ $prod->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-600">Cantidad</label>
                        <input type="number" name="lineas[0][cantidad]" min="1" value="1" required class="w-full px-2 py-1 text-sm border rounded">
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-600">Precio</label>
                        <input type="number" name="lineas[0][precio]" step="0.01" min="0" required class="w-full px-2 py-1 text-sm border rounded precioInput">
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-600">Descuento %</label>
                        <input type="number" name="lineas[0][descuento]" min="0" max="100" value="0" class="w-full px-2 py-1 text-sm border rounded">
                    </div>

                    <div class="flex items-end">
                        <button type="button" class="w-full px-2 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 removeLineBtn">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Resumen financiero -->
            <div class="mt-4 bg-white p-4 border rounded">
                <h4 class="font-semibold mb-2">Resumen</h4>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>Costos directos (S/.)</div>
                    <div class="text-right" id="costosDirectosDisplay">S/ 0.00</div>

                    <div>Gastos generales (6% CD)</div>
                    <div class="text-right" id="gastosGeneralesDisplay">S/ 0.00</div>

                    <div>Utilidad (10% CD)</div>
                    <div class="text-right" id="utilidadDisplay">S/ 0.00</div>

                    <div class="font-semibold">Subtotal</div>
                    <div class="text-right font-semibold" id="subtotalDisplay">S/ 0.00</div>

                    <div>IGV (18%)</div>
                    <div class="text-right" id="impuestoDisplay">S/ 0.00</div>

                    <div class="text-lg font-bold">Presupuesto Total</div>
                    <div class="text-right text-lg font-bold" id="totalDisplay">S/ 0.00</div>
                </div>

                <!-- Hidden inputs para envío (el servidor recalculará por seguridad) -->
                <input type="hidden" name="subtotal" id="input-subtotal" value="0">
                <input type="hidden" name="impuesto" id="input-impuesto" value="0">
                <input type="hidden" name="total" id="input-total" value="0">
            </div>
        </div>

        <!-- Botones -->
        <div class="flex gap-3 justify-end">
            <a href="{{ route('cotizaciones.index') }}" class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500">
                Cancelar
            </a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                <i class="fas fa-save mr-2"></i> Crear Cotización
            </button>
        </div>
    </form>
</div>

<script>
let lineIndex = 1;

document.getElementById('addLineBtn').addEventListener('click', function(e) {
    e.preventDefault();
    
    const container = document.getElementById('lineasContainer');
    const template = document.querySelector('.lineaItem').cloneNode(true);
    
    template.querySelectorAll('[name]').forEach(input => {
        const name = input.name;
        input.name = name.replace(/\[\d+\]/, `[${lineIndex}]`);
        if (input.type !== 'button' && !input.disabled) input.value = input.type === 'number' && name.includes('cantidad') ? '1' : '';
    });
    
    container.appendChild(template);
    attachLineListeners(template);
    lineIndex++;
});

function attachLineListeners(row) {
    const removeBtn = row.querySelector('.removeLineBtn');
    const productoSelect = row.querySelector('.productoSelect');
    const precioInput = row.querySelector('.precioInput');
    
    removeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (document.querySelectorAll('.lineaItem').length > 1) {
            row.remove();
                recalc();
        } else {
            alert('Debes mantener al menos un producto');
        }
    });
    productoSelect.addEventListener('change', function() {
        const precio = this.options[this.selectedIndex].dataset.precio || 0;
        precioInput.value = parseFloat(precio).toFixed(2);
            recalc();
    });

        // recalcular al cambiar cantidad, precio o descuento
        const qty = row.querySelector('input[name$="[cantidad]"]');
        const precio = row.querySelector('input[name$="[precio]"]');
        const desc = row.querySelector('input[name$="[descuento]"]');
        [qty, precio, desc].forEach(el => {
            if (!el) return;
            el.addEventListener('input', function() { recalc(); });
        });
}

document.querySelectorAll('.lineaItem').forEach(row => {
    attachLineListeners(row);
});
recalc();

function recalc() {
    let costosDirectos = 0;
    document.querySelectorAll('.lineaItem').forEach(row => {
        const qtyEl = row.querySelector('input[name$="[cantidad]"]');
        const priceEl = row.querySelector('input[name$="[precio]"]');
        const descEl = row.querySelector('input[name$="[descuento]"]');
        const qty = parseFloat(qtyEl?.value || 0) || 0;
        const price = parseFloat(priceEl?.value || 0) || 0;
        const desc = parseFloat(descEl?.value || 0) || 0;
        const subtotal = qty * price;
        const descuentoMonto = subtotal * (desc / 100);
        const subtotalFinal = subtotal - descuentoMonto;
        costosDirectos += subtotalFinal;
    });

    const gastosGenerales = parseFloat((costosDirectos * 0.06).toFixed(2));
    const utilidad = parseFloat((costosDirectos * 0.10).toFixed(2));
    const subtotalCalc = parseFloat((costosDirectos + gastosGenerales + utilidad).toFixed(2));
    const impuestoCalc = parseFloat((subtotalCalc * 0.18).toFixed(2));
    const totalCalc = parseFloat((subtotalCalc + impuestoCalc).toFixed(2));

    document.getElementById('costosDirectosDisplay').textContent = 'S/ ' + costosDirectos.toFixed(2);
    document.getElementById('gastosGeneralesDisplay').textContent = 'S/ ' + gastosGenerales.toFixed(2);
    document.getElementById('utilidadDisplay').textContent = 'S/ ' + utilidad.toFixed(2);
    document.getElementById('subtotalDisplay').textContent = 'S/ ' + subtotalCalc.toFixed(2);
    document.getElementById('impuestoDisplay').textContent = 'S/ ' + impuestoCalc.toFixed(2);
    document.getElementById('totalDisplay').textContent = 'S/ ' + totalCalc.toFixed(2);

    // hidden inputs
    document.getElementById('input-subtotal').value = subtotalCalc.toFixed(2);
    document.getElementById('input-impuesto').value = impuestoCalc.toFixed(2);
    document.getElementById('input-total').value = totalCalc.toFixed(2);
}
</script>

@endsection
