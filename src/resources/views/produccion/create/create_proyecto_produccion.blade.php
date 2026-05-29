@extends('layouts.app')
@section('title', 'Nuevo Proyecto de Producción')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('proyectos.tipo') }}" class="text-gray-500 hover:text-gray-700">← Volver</a>
        <h1 class="text-2xl font-bold text-gray-800">Nuevo Proyecto de Producción</h1>
    </div>

    <div class="flex gap-6">
        <div class="flex-1 bg-white rounded-xl shadow-sm border border-gray-100">
            <form action="{{ route('proyectos.store-produccion') }}" method="POST" class="p-6 space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nombre del Proyecto</label>
                    <input type="text" name="Nombre" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Cliente</label>
                    <select name="Id_Cliente" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">Seleccione un Cliente</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->Id_Cliente }}">{{ $cliente->Nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Fecha de Inicio</label>
                        <input type="date" name="Fecha_Inicio" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Fecha Fin</label>
                        <input type="date" name="Fecha_Fin" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Estado</label>
                    <select name="Estado" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="Pendiente">Pendiente</option>
                        <option value="En Progreso">En Progreso</option>
                        <option value="Completado">Completado</option>
                    </select>
                </div>

                <div class="border-t border-gray-200 pt-5">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Productos Asignados</h3>
                    <div id="productosSeleccionados" class="space-y-2">
                        <p class="text-gray-400 text-sm text-center py-4" id="sinProductosMsg">Ningún producto seleccionado. Usa el panel de la derecha para agregar productos.</p>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium transition">Guardar Proyecto</button>
                </div>
            </form>
        </div>

        <div class="w-96 space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 sticky top-6">
                <div class="p-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Productos en Inventario</h3>
                    <div class="mt-2 relative">
                        <input type="text" id="buscadorProductos" placeholder="Buscar productos..."
                               class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="max-h-96 overflow-y-auto p-2 space-y-1" id="listaProductos">
                    @forelse($productos as $producto)
                        <div class="producto-item flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition border border-transparent hover:border-indigo-200"
                             data-id="{{ $producto['Id_Producto'] }}"
                             data-nombre="{{ $producto['Nombre'] }}"
                             data-stock="{{ $producto['Stock_Total'] }}">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $producto['Nombre'] }}</p>
                                <p class="text-xs text-gray-500">
                                    <span>Código: {{ $producto['Codigo'] }}</span>
                                    <span class="ml-2">Stock: <span class="font-medium {{ $producto['Stock_Total'] > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $producto['Stock_Total'] }}</span></span>
                                </p>
                            </div>
                            <button type="button" class="agregar-producto text-indigo-600 hover:text-indigo-800 p-1.5 hover:bg-indigo-50 rounded-lg transition {{ $producto['Stock_Total'] <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $producto['Stock_Total'] <= 0 ? 'disabled' : '' }}>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm text-center py-4">No hay productos disponibles.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <h3 class="font-semibold text-gray-800 mb-3">Notificar Falta de Stock</h3>
                <div class="mb-2 relative">
                    <input type="text" id="buscadorNotificarStock" placeholder="Buscar..."
                           class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-1.5 text-xs focus:ring-2 focus:ring-indigo-500">
                    <svg class="absolute left-2.5 top-2 w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <form action="{{ route('proyectos.notificar-stock-general') }}" method="POST">
                    @csrf
                    <div id="notificarSeleccionContainer" class="space-y-2 mb-3">
                        <p class="text-xs text-gray-400">Selecciona un producto de la lista.</p>
                    </div>
                    <div class="max-h-40 overflow-y-auto space-y-1 mb-3" id="listaNotificarStock">
                        @foreach($productos as $p)
                            <div class="producto-item-notificar flex items-center justify-between p-2 rounded hover:bg-gray-50 cursor-pointer transition"
                                 data-id="{{ $p['Id_Producto'] }}"
                                 data-nombre="{{ $p['Nombre'] }}"
                                 data-stock="{{ $p['Stock_Total'] }}">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-gray-800 truncate">{{ $p['Nombre'] }}</p>
                                    <p class="text-xs text-gray-500">Stock: <span class="{{ $p['Stock_Total'] > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $p['Stock_Total'] }}</span></p>
                                </div>
                                <button type="button" class="btn-notificar-producto text-red-600 hover:text-red-800 p-1 hover:bg-red-50 rounded transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">Notificar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modalCantidad" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-sm w-full p-6 relative">
            <h3 class="text-lg font-semibold text-gray-800 mb-4" id="modalProductoNombre">Agregar Producto</h3>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Cantidad</label>
                <input type="number" id="modalCantidadInput" min="1" value="1"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="flex gap-3">
                <button type="button" id="modalCancelar" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition font-medium">Cancelar</button>
                <button type="button" id="modalConfirmar" class="flex-1 px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">Agregar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productosSeleccionados = {};
    let modalResolve = null;
    let modalProductoId = null;
    let modalProductoNombre = '';

    const modal = document.getElementById('modalCantidad');
    const modalInput = document.getElementById('modalCantidadInput');
    const modalNombre = document.getElementById('modalProductoNombre');
    const modalConfirmar = document.getElementById('modalConfirmar');
    const modalCancelar = document.getElementById('modalCancelar');

    function mostrarModal(nombre) {
        modalNombre.textContent = `Agregar: ${nombre}`;
        modalInput.value = 1;
        modal.classList.remove('hidden');
        return new Promise((resolve) => {
            modalResolve = resolve;
        });
    }

    function cerrarModal() {
        modal.classList.add('hidden');
        if (modalResolve) modalResolve(null);
    }

    modalConfirmar.addEventListener('click', function() {
        const cantidad = parseInt(modalInput.value) || 1;
        if (modalResolve) modalResolve(cantidad);
        modal.classList.add('hidden');
    });

    modalCancelar.addEventListener('click', cerrarModal);
    modal.addEventListener('click', function(e) {
        if (e.target === modal) cerrarModal();
    });

    document.querySelectorAll('.agregar-producto').forEach(function(btn) {
        btn.addEventListener('click', async function() {
            if (this.disabled) return;
            const item = this.closest('.producto-item');
            const id = item.dataset.id;
            const nombre = item.dataset.nombre;
            const stockMax = parseInt(item.dataset.stock);

            const cantidad = await mostrarModal(nombre);
            if (!cantidad || cantidad < 1) return;

            if (cantidad > stockMax) {
                alert('Stock insuficiente. Stock disponible: ' + stockMax);
                return;
            }

            if (productosSeleccionados[id]) {
                productosSeleccionados[id].Cantidad += cantidad;
            } else {
                productosSeleccionados[id] = { Id_Producto: id, Nombre: nombre, Cantidad: cantidad, Stock: stockMax };
            }

            renderizarProductos();
        });
    });

    document.getElementById('buscadorProductos').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.producto-item').forEach(function(item) {
            const nombre = item.dataset.nombre.toLowerCase();
            item.style.display = nombre.includes(query) ? '' : 'none';
        });
    });

    function renderizarProductos() {
        const container = document.getElementById('productosSeleccionados');
        const sinMsg = document.getElementById('sinProductosMsg');
        const ids = Object.keys(productosSeleccionados);

        if (ids.length === 0) {
            sinMsg.style.display = '';
            container.querySelectorAll('.producto-seleccionado').forEach(el => el.remove());
            return;
        }

        sinMsg.style.display = 'none';
        container.querySelectorAll('.producto-seleccionado').forEach(el => el.remove());

        ids.forEach(function(id) {
            const p = productosSeleccionados[id];
            const div = document.createElement('div');
            div.className = 'producto-seleccionado flex items-center justify-between bg-gray-50 rounded-lg p-3 border border-gray-100';
            div.innerHTML = `
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800">${p.Nombre}</p>
                    <p class="text-xs text-gray-500">Cantidad: <span class="font-medium text-indigo-600">${p.Cantidad}</span></p>
                    <input type="hidden" name="productos[${id}][Id_Producto]" value="${id}">
                    <input type="hidden" name="productos[${id}][Cantidad]" value="${p.Cantidad}">
                </div>
                <button type="button" class="quitar-producto text-red-500 hover:text-red-700 p-1.5 hover:bg-red-50 rounded-lg transition" data-id="${id}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;
            container.appendChild(div);

            div.querySelector('.quitar-producto').addEventListener('click', function() {
                delete productosSeleccionados[this.dataset.id];
                renderizarProductos();
            });
        });
    }
});
</script>

<script src="{{ asset('js/editar-proyecto-produccion.js') }}"></script>
@endsection
