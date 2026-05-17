@extends('layouts.app')
@section('title', 'Nuevo Servicio')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('proyectos.tipo') }}" class="text-gray-500 hover:text-gray-700">← Volver</a>
        <h1 class="text-2xl font-bold text-gray-800">Nuevo Servicio</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 max-w-2xl">
        <form action="{{ route('proyectos.store-servicio') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nombre del Servicio</label>
                <input type="text" name="Nombre" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Cliente</label>
                <select name="Id_Cliente" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                    <option value="">Seleccione un Cliente</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->Id_Cliente }}">{{ $cliente->Nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Fecha de Inicio</label>
                    <input type="date" name="Fecha_Inicio" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Fecha Fin</label>
                    <input type="date" name="Fecha_Fin" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Estado</label>
                <select name="Estado" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="Pendiente">Pendiente</option>
                    <option value="En Progreso">En Progreso</option>
                    <option value="Completado">Completado</option>
                </select>
            </div>

            <div class="border-t border-gray-200 pt-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Gastos del Servicio</h3>
                    <button type="button" id="agregarGasto" class="text-emerald-600 hover:text-emerald-800 font-medium text-sm flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Agregar Gasto
                    </button>
                </div>
                <div id="gastosContainer" class="space-y-3">
                    <div class="gasto-item flex gap-3 items-start">
                        <div class="flex-1">
                            <input type="text" name="gastos[0][Descripcion]" placeholder="Descripción del gasto"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div class="w-48">
                            <input type="number" step="0.01" min="0" name="gastos[0][Monto]" placeholder="Monto"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <button type="button" class="quitar-gasto p-2.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div id="totalGastos" class="mt-4 text-right text-sm text-gray-600">
                    Total: <span id="totalMonto" class="font-semibold text-gray-900">S/ 0.00</span>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-lg font-medium transition">Guardar Servicio</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let gastoIndex = 1;

    document.getElementById('agregarGasto').addEventListener('click', function() {
        const container = document.getElementById('gastosContainer');
        const div = document.createElement('div');
        div.className = 'gasto-item flex gap-3 items-start';
        div.innerHTML = `
            <div class="flex-1">
                <input type="text" name="gastos[${gastoIndex}][Descripcion]" placeholder="Descripción del gasto"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div class="w-48">
                <input type="number" step="0.01" min="0" name="gastos[${gastoIndex}][Monto]" placeholder="Monto"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <button type="button" class="quitar-gasto p-2.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;
        container.appendChild(div);
        gastoIndex++;
        actualizarTotal();
    });

    document.getElementById('gastosContainer').addEventListener('click', function(e) {
        if (e.target.closest('.quitar-gasto')) {
            e.target.closest('.gasto-item').remove();
            actualizarTotal();
        }
    });

    document.getElementById('gastosContainer').addEventListener('input', function(e) {
        if (e.target.name && e.target.name.includes('Monto')) {
            actualizarTotal();
        }
    });

    function actualizarTotal() {
        let total = 0;
        document.querySelectorAll('input[name$="[Monto]"]').forEach(function(input) {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('totalMonto').textContent = 'S/ ' + total.toFixed(2);
    }
});
</script>
@endsection
