@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- ENCABEZADO --}}
    <div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <span class="breadcrumb">Módulo de Contabilidad</span>
            <h2 class="title flex items-center gap-2">
                <span>📒</span> Plan de Cuentas (PCGE)
            </h2>
            <p class="subtitle">Catálogo oficial de cuentas contables del sistema</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="document.getElementById('formNuevaCuenta').classList.toggle('hidden')" 
                    class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Nueva Cuenta
            </button>
            <a href="{{ route('asientos.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
        </div>
    </div>

    {{-- ALERTAS --}}
    @if(session('success'))
        <div class="alert alert-success animate-fade-in">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger animate-fade-in">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
    @endif

    {{-- FORMULARIO NUEVA CUENTA (Colapsable) --}}
    <div id="formNuevaCuenta" class="hidden mb-6">
        <div class="card animate-slide-up">
            <div class="card-header bg-blue-600 text-white flex items-center gap-2">
                <i class="fas fa-plus-circle"></i>
                <span>Registrar Nueva Cuenta Contable</span>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('contabilidad.plan_cuentas.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                        {{-- Código --}}
                        <div class="md:col-span-3">
                            <label class="form-label">
                                Código PCGE <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="Codigo" 
                                   class="form-input font-mono"
                                   placeholder="Ej: 101, 4011, 701..." 
                                   value="{{ old('Codigo') }}" required>
                            <p class="text-xs text-gray-400 mt-1">Código numérico del PCGE peruano</p>
                        </div>
                        
                        {{-- Nombre --}}
                        <div class="md:col-span-5">
                            <label class="form-label">
                                Nombre de la Cuenta <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="Nombre_Cuenta" 
                                   class="form-input"
                                   placeholder="Ej: Caja y Bancos" 
                                   value="{{ old('Nombre_Cuenta') }}" required>
                        </div>
                        
                        {{-- Tipo --}}
                        <div class="md:col-span-3">
                            <label class="form-label">
                                Tipo <span class="text-red-500">*</span>
                            </label>
                            <select name="Tipo" class="form-select" required>
                                <option value="">— Seleccionar —</option>
                                @foreach(['Activo','Activo (Contra)','Pasivo','Patrimonio','Ingreso','Gasto','Costo'] as $tipo)
                                    <option value="{{ $tipo }}" {{ old('Tipo') == $tipo ? 'selected' : '' }}>
                                        {{ $tipo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- Botón --}}
                        <div class="md:col-span-1">
                            <button type="submit" class="btn btn-success w-full">
                                <i class="fas fa-save"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- TABLA DE CUENTAS AGRUPADA POR TIPO --}}
    @php
        $grupos = [
            'Activo'         => ['color_bg' => 'bg-blue-50', 'color_border' => 'border-blue-500', 'color_text' => 'text-blue-700', 'color_light' => 'bg-blue-600', 'icono' => '🏦'],
            'Activo (Contra)'=> ['color_bg' => 'bg-amber-50', 'color_border' => 'border-amber-500', 'color_text' => 'text-amber-700', 'color_light' => 'bg-amber-500', 'icono' => '⬇️'],
            'Pasivo'         => ['color_bg' => 'bg-orange-50', 'color_border' => 'border-orange-500', 'color_text' => 'text-orange-700', 'color_light' => 'bg-orange-600', 'icono' => '💳'],
            'Patrimonio'     => ['color_bg' => 'bg-purple-50', 'color_border' => 'border-purple-500', 'color_text' => 'text-purple-700', 'color_light' => 'bg-purple-600', 'icono' => '🏛️'],
            'Ingreso'        => ['color_bg' => 'bg-teal-50', 'color_border' => 'border-teal-500', 'color_text' => 'text-teal-700', 'color_light' => 'bg-teal-600', 'icono' => '💰'],
            'Gasto'          => ['color_bg' => 'bg-gray-50', 'color_border' => 'border-gray-400', 'color_text' => 'text-gray-600', 'color_light' => 'bg-gray-500', 'icono' => '💸'],
            'Costo'          => ['color_bg' => 'bg-rose-50', 'color_border' => 'border-rose-400', 'color_text' => 'text-rose-700', 'color_light' => 'bg-rose-600', 'icono' => '📦'],
        ];
    @endphp

    @foreach($grupos as $tipo => $estilo)
        @if(isset($agrupadas[$tipo]) && $agrupadas[$tipo]->count() > 0)
        <div class="card mb-4 border-l-4 {{ $estilo['color_border'] }}">
            {{-- Cabecera del grupo --}}
            <div class="{{ $estilo['color_light'] }} text-white px-5 py-3 flex justify-between items-center rounded-t-xl">
                <span class="font-semibold text-sm">
                    {{ $estilo['icono'] }} {{ $tipo }}
                </span>
                <span class="bg-white/20 text-white text-xs font-medium px-3 py-1 rounded-full">
                    {{ $agrupadas[$tipo]->count() }} cuentas
                </span>
            </div>
            
            {{-- Tabla --}}
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-1/6 pl-5">Código</th>
                            <th>Nombre de la Cuenta</th>
                            <th class="w-1/6 text-center">Movimientos</th>
                            <th class="w-1/6 text-center pr-5">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agrupadas[$tipo]->sortBy('Codigo') as $cuenta)
                        <tr class="hover:{{ $estilo['color_bg'] }} transition-colors duration-150">
                            <td class="pl-5">
                                <span class="font-mono font-bold text-sm {{ $estilo['color_text'] }}">
                                    {{ $cuenta->Codigo }}
                                </span>
                            </td>
                            <td class="text-gray-700">{{ $cuenta->Nombre_Cuenta }}</td>
                            <td class="text-center">
                                @php
                                    $count = $cuenta->detalles_count ?? $cuenta->detalles()->count();
                                @endphp
                                @if($count > 0)
                                    <span class="badge badge-info">{{ $count }}</span>
                                @else
                                    <span class="badge badge-gray">0</span>
                                @endif
                            </td>
                            <td class="text-center pr-5">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('contabilidad.plan_cuentas.edit', $cuenta->Id_Cuenta) }}" 
                                       class="btn btn-ghost btn-sm text-gray-500 hover:text-blue-600"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('contabilidad.plan_cuentas.destroy', $cuenta->Id_Cuenta) }}" 
                                          method="POST" class="inline"
                                          onsubmit="return confirm('¿Eliminar la cuenta {{ $cuenta->Codigo }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-ghost btn-sm text-gray-500 hover:text-red-600"
                                                title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    @endforeach

    {{-- Estado vacío --}}
    @if($cuentas->isEmpty())
        <div class="text-center py-16">
            <div class="text-6xl mb-4">📋</div>
            <h3 class="text-lg font-semibold text-gray-600 mb-2">No hay cuentas registradas</h3>
            <p class="text-gray-400 mb-4">Empieza añadiendo las cuentas del PCGE</p>
            <button onclick="document.getElementById('formNuevaCuenta').classList.remove('hidden')" 
                    class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Crear Primera Cuenta
            </button>
        </div>
    @endif

</div>

{{-- Script para manejar el colapso del formulario --}}
<script>
    // Si hay errores de validación, mostrar el formulario automáticamente
    @if($errors->any())
        document.getElementById('formNuevaCuenta').classList.remove('hidden');
    @endif
    
    // Si hay old input, también mostrar el formulario
    @if(old('Codigo') || old('Nombre_Cuenta'))
        document.getElementById('formNuevaCuenta').classList.remove('hidden');
    @endif
</script>
@endsection