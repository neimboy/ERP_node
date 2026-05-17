@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Registrar Nueva Compra</h1>

    <form action="{{ route('compras.store') }}" method="POST">
        @csrf

        <!-- Proveedor -->
        <div class="mb-4">
            <label for="Id_Proveedor" class="block text-gray-700 font-semibold mb-2">Proveedor</label>
            <select name="Id_Proveedor" id="Id_Proveedor" 
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                <option value="">Seleccione un proveedor</option>
                @foreach($proveedores as $proveedor)
                    <option value="{{ $proveedor->Id_Proveedor }}">{{ $proveedor->Nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Almacén -->
        <div class="mb-4">
            <label for="Id_Almacen" class="block text-gray-700 font-semibold mb-2">Almacén</label>
            <select name="Id_Almacen" id="Id_Almacen" 
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                <option value="">Seleccione un almacén</option>
                @foreach($almacenes as $almacen)
                    <option value="{{ $almacen->Id_Almacen }}">{{ $almacen->Nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Fecha -->
        <div class="mb-4">
            <label for="Fecha" class="block text-gray-700 font-semibold mb-2">Fecha</label>
            <input type="date" name="Fecha" id="Fecha" 
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        <!-- Estado -->
        <div class="mb-4">
            <label for="Estado" class="block text-gray-700 font-semibold mb-2">Estado</label>
            <select name="Estado" id="Estado" 
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="Pendiente">Pendiente</option>
                <option value="Recibida">Recibida</option>
                <option value="Cancelada">Cancelada</option>
            </select>
        </div>

        <!-- Productos -->
        <h3 class="text-xl font-semibold mt-6 mb-3 text-gray-800">Productos</h3>
        <div id="productos-container" class="space-y-3">
            <div class="grid grid-cols-3 gap-3 producto-row">
                <div>
                    <select name="productos[0][Id_Producto]" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 producto-select" required>
                        <option value="">Seleccione un producto</option>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->Id_Producto }}" data-precio="{{ $producto->Precio_Compra }}">
                                {{ $producto->Nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <input type="number" name="productos[0][Cantidad]" placeholder="Cantidad" 
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <input type="number" step="0.01" name="productos[0][Precio]" placeholder="Precio" 
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 precio-input" readonly>
                </div>
            </div>
        </div>

        <!-- Botón para agregar más productos -->
        <div class="mt-4">
            <button type="button" id="add-producto" 
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Agregar otro producto
            </button>
        </div>

        <!-- Botones -->
        <div class="flex space-x-3 mt-6">
            <button type="submit" 
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                Guardar Compra
            </button>
            <a href="{{ route('compras.index') }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
               Cancelar
            </a>
        </div>
    </form>
</div>

<!-- Script para autocompletar precio -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Autocompletar precio al seleccionar producto
    function bindPrecio(select) {
        select.addEventListener('change', function() {
            const precio = this.options[this.selectedIndex].dataset.precio;
            const precioInput = this.closest('.grid').querySelector('.precio-input');
            if (precioInput) precioInput.value = precio;
        });
    }

    document.querySelectorAll('.producto-select').forEach(bindPrecio);

    // Botón para agregar más productos
    let index = 1;
    document.getElementById('add-producto').addEventListener('click', () => {
        const container = document.getElementById('productos-container');
        const firstRow = container.querySelector('.producto-row');
        const newRow = firstRow.cloneNode(true);

        // Actualizar los nombres de los inputs con el nuevo índice
        newRow.querySelectorAll('select, input').forEach(input => {
            input.name = input.name.replace(/\d+/, index);
            if (input.type === 'number') input.value = '';
        });

        container.appendChild(newRow);

        // Re-bind del evento de precio
        newRow.querySelectorAll('.producto-select').forEach(bindPrecio);

        index++;
    });
});
</script>

@endsection
