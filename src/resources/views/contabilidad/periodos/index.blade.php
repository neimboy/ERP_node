@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- ENCABEZADO --}}
    <div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="breadcrumb">Módulo de Contabilidad</span>
            <h2 class="title flex items-center gap-2">
                <span>📅</span> Períodos Contables
            </h2>
            <p class="subtitle">Gestión de períodos mensuales — abre o cierra cada mes para controlar los asientos</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="toggleFormulario()" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Nuevo Período
            </button>
            <a href="{{ route('asientos.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
        </div>
    </div>

    {{-- ALERTAS --}}
    @if(session('success'))
        <div class="alert alert-success animate-fade-in flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger animate-fade-in flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
    
    @if(session('warning'))
        <div class="alert alert-warning animate-fade-in flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ session('warning') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-amber-600 hover:text-amber-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- FORMULARIO NUEVO PERÍODO (Colapsable) --}}
    <div id="formNuevoPeriodo" class="hidden mb-6">
        <div class="card animate-slide-up">
            <div class="bg-blue-600 text-white px-5 py-4 rounded-t-xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-xl">📅</span>
                    <span class="font-semibold">Crear Nuevo Período Contable</span>
                </div>
                <button onclick="toggleFormulario()" class="text-white/80 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-5">
                <form action="{{ route('contabilidad.periodos.store') }}" method="POST">
                    @csrf
                    
                    {{-- Sugerencia de siguiente período --}}
                    @php
                        $ultimoPeriodo = $periodos->first();
                        $siguienteMes = $ultimoPeriodo ? ($ultimoPeriodo->Mes == 12 ? 1 : $ultimoPeriodo->Mes + 1) : date('n');
                        $siguienteAnio = $ultimoPeriodo ? ($ultimoPeriodo->Mes == 12 ? $ultimoPeriodo->Año + 1 : $ultimoPeriodo->Año) : date('Y');
                    @endphp
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-5 text-sm text-blue-700 flex items-center gap-2">
                        <i class="fas fa-lightbulb"></i>
                        <span>
                            <strong>Sugerencia:</strong> El siguiente período lógico es 
                            <span class="font-mono font-bold">{{ str_pad($siguienteMes, 2, '0', STR_PAD_LEFT) }}/{{ $siguienteAnio }}</span>
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                        {{-- Año --}}
                        <div>
                            <label for="Año" class="form-label">
                                Año <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="Año" 
                                   id="Año"
                                   class="form-input"
                                   value="{{ old('Año', $siguienteAnio) }}"
                                   min="2000" 
                                   max="2100" 
                                   required>
                        </div>

                        {{-- Mes --}}
                        <div>
                            <label for="Mes" class="form-label">
                                Mes <span class="text-red-500">*</span>
                            </label>
                            <select name="Mes" id="Mes" class="form-select" required>
                                <option value="">— Seleccionar —</option>
                                @foreach([
                                    1=>'Enero', 2=>'Febrero', 3=>'Marzo', 4=>'Abril',
                                    5=>'Mayo', 6=>'Junio', 7=>'Julio', 8=>'Agosto',
                                    9=>'Septiembre', 10=>'Octubre', 11=>'Noviembre', 12=>'Diciembre'
                                ] as $num => $nombre)
                                    <option value="{{ $num }}" {{ old('Mes', $siguienteMes) == $num ? 'selected' : '' }}>
                                        {{ str_pad($num, 2, '0', STR_PAD_LEFT) }} – {{ $nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Botón --}}
                        <div>
                            <button type="submit" class="btn btn-success w-full">
                                <i class="fas fa-check mr-2"></i> Crear Período
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- TABLA DE PERÍODOS --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th class="pl-5 w-16">#</th>
                        <th class="w-48">Período</th>
                        <th class="text-center w-36">Estado</th>
                        <th class="text-center w-28">Asientos</th>
                        <th class="text-center pr-5 w-72">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($periodos as $periodo)
                        <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                            {{-- ID --}}
                            <td class="pl-5">
                                <span class="font-mono text-sm text-gray-400">#{{ $periodo->Id_Periodo }}</span>
                            </td>

                            {{-- Nombre del período --}}
                            <td>
                                <span class="font-semibold text-gray-700">
                                    {{ $periodo->label }}
                                </span>
                            </td>

                            {{-- Estado --}}
                            <td class="text-center">
                                @if($periodo->Estado === 'Abierto')
                                    <span class="periodo-abierto text-sm">
                                        Abierto
                                    </span>
                                @else
                                    <span class="periodo-cerrado text-sm">
                                        Cerrado
                                    </span>
                                @endif
                            </td>

                            {{-- Cantidad de asientos --}}
                            <td class="text-center">
                                @if($periodo->asientos_count > 0)
                                    <span class="badge badge-info">
                                        {{ $periodo->asientos_count }}
                                    </span>
                                @else
                                    <span class="badge badge-gray">
                                        0
                                    </span>
                                @endif
                            </td>

                            {{-- Acciones --}}
                            <td class="text-center pr-5">
                                <div class="flex items-center justify-center gap-1.5">
                                    
                                    {{-- Abrir/Cerrar --}}
                                    <form action="{{ route('contabilidad.periodos.toggle', $periodo->Id_Periodo) }}"
                                          method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        @if($periodo->Estado === 'Abierto')
                                            <button type="submit"
                                                    class="btn btn-sm bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200"
                                                    onclick="return confirm('¿Cerrar el período {{ $periodo->label }}?\n\nUna vez cerrado, no se podrán registrar ni modificar asientos en este mes.')">
                                                <i class="fas fa-lock mr-1"></i> Cerrar
                                            </button>
                                        @else
                                            <button type="submit"
                                                    class="btn btn-sm bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200"
                                                    onclick="return confirm('¿Reabrir el período {{ $periodo->label }}?\n\nEsto permitirá registrar nuevos asientos en este mes.')">
                                                <i class="fas fa-unlock mr-1"></i> Reabrir
                                            </button>
                                        @endif
                                    </form>

                                    {{-- Ver Libro Mayor --}}
                                    <a href="{{ route('contabilidad.libro_mayor') }}?Id_Periodo={{ $periodo->Id_Periodo }}"
                                       class="btn btn-ghost btn-sm text-indigo-600 hover:bg-indigo-50"
                                       title="Ver Libro Mayor de {{ $periodo->label }}">
                                        <i class="fas fa-book-open mr-1"></i> Mayor
                                    </a>

                                    {{-- Ver Asientos del período --}}
                                    <a href="{{ route('asientos.index') }}?periodo={{ $periodo->Id_Periodo }}"
                                       class="btn btn-ghost btn-sm text-blue-600 hover:bg-blue-50"
                                       title="Ver asientos de {{ $periodo->label }}">
                                        <i class="fas fa-list mr-1"></i> Asientos
                                    </a>

                                    {{-- Eliminar (solo si no tiene asientos) --}}
                                    @if($periodo->asientos_count === 0)
                                        <form action="{{ route('contabilidad.periodos.destroy', $periodo->Id_Periodo) }}"
                                              method="POST" class="inline"
                                              onsubmit="return confirm('¿Eliminar el período {{ $periodo->label }}?\n\nEsta acción no se puede deshacer.')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-ghost btn-sm text-red-500 hover:bg-red-50"
                                                    title="Eliminar período">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-16">
                                <div class="flex flex-col items-center">
                                    <span class="text-5xl mb-4">📅</span>
                                    <h3 class="text-lg font-semibold text-gray-600 mb-2">
                                        No hay períodos contables creados
                                    </h3>
                                    <p class="text-gray-400 mb-4">
                                        Crea tu primer período mensual para comenzar a registrar asientos
                                    </p>
                                    <button onclick="toggleFormulario()" class="btn btn-primary">
                                        <i class="fas fa-plus mr-2"></i> Crear Primer Período
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- LEYENDA INFORMATIVA --}}
    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <span class="text-xl">📌</span>
            <div class="text-sm text-blue-800">
                <p class="font-semibold mb-1">¿Cómo funcionan los períodos?</p>
                <ul class="space-y-1 text-blue-700">
                    <li>• Solo se pueden registrar asientos en períodos 
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Abiertos</span>
                    </li>
                    <li>• Al <strong>cerrar</strong> un período, ya no se pueden añadir ni modificar asientos en ese mes</li>
                    <li>• No se puede cerrar un período que contenga asientos descuadrados</li>
                    <li>• Si necesitas corregir algo en un período cerrado, usa <strong>Reabrir</strong></li>
                    <li>• Solo se pueden eliminar períodos que no tengan asientos registrados</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- RESUMEN RÁPIDO --}}
    @if($periodos->count() > 0)
        @php
            $abiertos = $periodos->where('Estado', 'Abierto')->count();
            $cerrados = $periodos->where('Estado', 'Cerrado')->count();
            $totalAsientos = $periodos->sum('asientos_count');
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
            <div class="kpi-card kpi-default text-center">
                <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Períodos Abiertos</span>
                <div class="kpi-value text-emerald-600">{{ $abiertos }}</div>
            </div>
            <div class="kpi-card kpi-default text-center">
                <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Períodos Cerrados</span>
                <div class="kpi-value text-gray-600">{{ $cerrados }}</div>
            </div>
            <div class="kpi-card kpi-default text-center">
                <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Total Asientos</span>
                <div class="kpi-value text-blue-600">{{ $totalAsientos }}</div>
            </div>
        </div>
    @endif

</div>

{{-- SCRIPT PARA EL FORMULARIO COLAPSABLE --}}
<script>
    function toggleFormulario() {
        const form = document.getElementById('formNuevoPeriodo');
        form.classList.toggle('hidden');
        if (!form.classList.contains('hidden')) {
            form.scrollIntoView({ behavior: 'smooth', block: 'center' });
            // Enfocar el primer campo
            setTimeout(() => form.querySelector('input[name="Año"]').focus(), 300);
        }
    }
    
    // Si hay errores de validación, mostrar el formulario automáticamente
    @if($errors->any())
        document.getElementById('formNuevoPeriodo').classList.remove('hidden');
    @endif
    
    // Si hay old input, también mostrar
    @if(old('Año') || old('Mes'))
        document.getElementById('formNuevoPeriodo').classList.remove('hidden');
    @endif
</script>
@endsection