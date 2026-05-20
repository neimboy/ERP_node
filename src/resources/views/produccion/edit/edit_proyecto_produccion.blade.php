@extends('layouts.app')
@section('title', 'Editar Proyecto de Producción')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('proyectos.show', $proyecto->Id_Proyecto) }}" class="text-gray-500 hover:text-gray-700">← Volver</a>
        <h1 class="text-2xl font-bold text-gray-800">Editar: {{ $proyecto->Nombre }}</h1>
    </div>

    <div class="flex gap-6">
        <div class="flex-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <form action="{{ route('proyectos.update-produccion', $proyecto->Id_Proyecto) }}" method="POST" class="p-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nombre del Proyecto</label>
                        <input type="text" name="Nombre" value="{{ $proyecto->Nombre }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cliente</label>
                        <select name="Id_Cliente" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->Id_Cliente }}" {{ $cliente->Id_Cliente == $proyecto->Id_Cliente ? 'selected' : '' }}>
                                    {{ $cliente->Nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Fecha de Inicio</label>
                            <input type="date" name="Fecha_Inicio" value="{{ $proyecto->Fecha_Inicio }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Fecha Fin</label>
                            <input type="date" name="Fecha_Fin" value="{{ $proyecto->Fecha_Fin }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Estado</label>
                        <select name="Estado" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="Pendiente" {{ $proyecto->Estado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="En Progreso" {{ $proyecto->Estado == 'En Progreso' ? 'selected' : '' }}>En Progreso</option>
                            <option value="Completado" {{ $proyecto->Estado == 'Completado' ? 'selected' : '' }}>Completado</option>
                        </select>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium transition">Actualizar Proyecto</button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Productos Actuales</h3>
                @if($proyecto->productos->isEmpty())
                    <p class="text-gray-400 text-sm">No hay productos asignados a este proyecto.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($proyecto->productos as $producto)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-800">{{ $producto->Nombre }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $producto->pivot->Cantidad }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="w-80 space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <h3 class="font-semibold text-gray-800 mb-3">Agregar Más Productos</h3>
                <form action="{{ route('proyectos.agregar-productos', $proyecto->Id_Proyecto) }}" method="POST">
                    @csrf
                    <div id="nuevosProductosContainer" class="space-y-2 mb-3">
                        <p class="text-xs text-gray-400" id="sinNuevosMsg">Selecciona productos del panel derecho.</p>
                    </div>
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">Agregar Productos</button>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <h3 class="font-semibold text-gray-800 mb-3">Devolver Productos</h3>
                <form action="{{ route('proyectos.devolver-productos', $proyecto->Id_Proyecto) }}" method="POST">
                    @csrf
                    <div id="devolverProductosContainer" class="space-y-2 mb-3">
                        @foreach($proyecto->productos as $producto)
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-600 flex-1 truncate">{{ $producto->Nombre }}</span>
                            <input type="number" name="productos[{{ $loop->index }}][Id_Producto]" value="{{ $producto->Id_Producto }}" hidden>
                            <input type="number" name="productos[{{ $loop->index }}][Cantidad]" min="1" max="{{ $producto->pivot->Cantidad }}" placeholder="Cant"
                                   class="w-16 border border-gray-300 rounded px-2 py-1 text-xs">
                        </div>
                        @endforeach
                    </div>
                    <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition"
                            onclick="return confirm('¿Devolver estos productos al inventario?')">Devolver al Inventario</button>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <h3 class="font-semibold text-gray-800 mb-3">Notificar Falta de Stock</h3>
                <form action="{{ route('proyectos.notificar-stock', $proyecto->Id_Proyecto) }}" method="POST">
                    @csrf
                    <div class="space-y-2 mb-3">
                        <select name="Id_Producto" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500" required>
                            <option value="">Seleccionar producto</option>
                            @foreach($productos as $p)
                                <option value="{{ $p['Id_Producto'] }}">{{ $p['Nombre'] }} (Stock: {{ $p['Stock_Total'] }})</option>
                            @endforeach
                        </select>
                        <input type="number" name="Cantidad" min="1" placeholder="Cantidad requerida"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500" required>
                    </div>
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">Notificar</button>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="w-72">
                    <div class="p-3 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800 text-sm">Productos en Inventario</h3>
                        <div class="mt-2 relative">
                            <input type="text" id="buscadorProductosEdit" placeholder="Buscar..."
                                   class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-1.5 text-xs focus:ring-2 focus:ring-indigo-500">
                            <svg class="absolute left-2.5 top-2 w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="max-h-60 overflow-y-auto space-y-1" id="listaProductosEdit">
                        @forelse($productos as $producto)
                            <div class="producto-item-edit flex items-center justify-between p-2 rounded hover:bg-gray-50 cursor-pointer transition"
                                 data-id="{{ $producto['Id_Producto'] }}"
                                 data-nombre="{{ $producto['Nombre'] }}"
                                 data-stock="{{ $producto['Stock_Total'] }}">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-gray-800 truncate">{{ $producto['Nombre'] }}</p>
                                    <p class="text-xs text-gray-500">Stock: <span class="{{ $producto['Stock_Total'] > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $producto['Stock_Total'] }}</span></p>
                                </div>
                                <button type="button" class="agregar-nuevo-producto text-indigo-600 hover:text-indigo-800 p-1 hover:bg-indigo-50 rounded transition {{ $producto['Stock_Total'] <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    {{ $producto['Stock_Total'] <= 0 ? 'disabled' : '' }}>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>
                        @empty
                            <p class="text-gray-400 text-xs text-center py-4">Sin productos.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <form action="{{ route('proyectos.destroy', $proyecto->Id_Proyecto) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium" onclick="return confirm('¿Estás seguro de eliminar este proyecto?')">Eliminar proyecto</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productosNuevos = {};

    document.querySelectorAll('.agregar-nuevo-producto').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (this.disabled) return;
            const item = this.closest('.producto-item-edit');
            const id = item.dataset.id;
            const nombre = item.dataset.nombre;
            const stockMax = parseInt(item.dataset.stock);

            const cantidad = prompt('Cantidad para "' + nombre + '":', '1');
            if (!cantidad || isNaN(cantidad) || parseInt(cantidad) < 1) return;

            const cantNum = parseInt(cantidad);
            if (cantNum > stockMax) {
                alert('Stock insuficiente. Disponible: ' + stockMax);
                return;
            }

            if (productosNuevos[id]) {
                productosNuevos[id].Cantidad += cantNum;
            } else {
                productosNuevos[id] = { Id_Producto: id, Nombre: nombre, Cantidad: cantNum };
            }

            renderizarNuevosProductos();
        });
    });

    document.getElementById('buscadorProductosEdit').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.producto-item-edit').forEach(function(item) {
            item.style.display = item.dataset.nombre.toLowerCase().includes(q) ? '' : 'none';
        });
    });

    function renderizarNuevosProductos() {
        const container = document.getElementById('nuevosProductosContainer');
        const sinMsg = document.getElementById('sinNuevosMsg');
        const ids = Object.keys(productosNuevos);

        container.querySelectorAll('.nuevo-producto-item').forEach(el => el.remove());

        if (ids.length === 0) {
            sinMsg.style.display = '';
            return;
        }

        sinMsg.style.display = 'none';

        ids.forEach(function(id) {
            const p = productosNuevos[id];
            const div = document.createElement('div');
            div.className = 'nuevo-producto-item flex items-center justify-between bg-green-50 rounded p-2';
            div.innerHTML = `
                <span class="text-xs font-medium text-gray-700">${p.Nombre} x${p.Cantidad}</span>
                <button type="button" class="quitar-nuevo text-red-500 hover:text-red-700" data-id="${id}">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <input type="hidden" name="nuevos_productos[${id}][Id_Producto]" value="${id}">
                <input type="hidden" name="nuevos_productos[${id}][Cantidad]" value="${p.Cantidad}">
            `;
            container.appendChild(div);

            div.querySelector('.quitar-nuevo').addEventListener('click', function() {
                delete productosNuevos[this.dataset.id];
                renderizarNuevosProductos();
            });
        });
    }
});
</script>
@endsection
