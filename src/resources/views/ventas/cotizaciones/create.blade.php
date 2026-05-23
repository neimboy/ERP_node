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
        } else {
            alert('Debes mantener al menos un producto');
        }
    });
    
    productoSelect.addEventListener('change', function() {
        const precio = this.options[this.selectedIndex].dataset.precio || 0;
        precioInput.value = parseFloat(precio).toFixed(2);
    });
}

document.querySelectorAll('.lineaItem').forEach(row => {
    attachLineListeners(row);
});
</script>

@endsection
