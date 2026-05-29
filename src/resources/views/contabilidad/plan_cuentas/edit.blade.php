@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto">

    {{-- Tarjeta principal --}}
    <div class="card animate-fade-in">
        
        {{-- Cabecera --}}
        <div class="bg-amber-500 text-white px-5 py-4 rounded-t-xl flex items-center gap-3">
            <span class="text-xl">✏️</span>
            <div>
                <span class="font-semibold text-sm">Editar Cuenta Contable</span>
                <span class="block text-white/80 text-xs font-mono mt-0.5">{{ $cuenta->Codigo }}</span>
            </div>
        </div>

        {{-- Cuerpo --}}
        <div class="p-6">
            
            {{-- Errores de validación --}}
            @if($errors->any())
                <div class="alert alert-danger mb-5">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-exclamation-triangle mt-0.5"></i>
                        <div>
                            <p class="font-semibold text-sm mb-1">Corrige los siguientes errores:</p>
                            <ul class="list-disc list-inside text-sm space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Formulario --}}
            <form action="{{ route('contabilidad.plan_cuentas.update', $cuenta->Id_Cuenta) }}" method="POST">
                @csrf 
                @method('PUT')

                <div class="space-y-5">
                    
                    {{-- Código PCGE --}}
                    <div>
                        <label for="Codigo" class="form-label">
                            Código PCGE <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fas fa-hashtag text-sm"></i>
                            </span>
                            <input type="text" 
                                   name="Codigo" 
                                   id="Codigo"
                                   class="form-input font-mono pl-9 tracking-wider"
                                   value="{{ old('Codigo', $cuenta->Codigo) }}" 
                                   required
                                   autofocus>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Código único del plan contable</p>
                    </div>

                    {{-- Nombre de la Cuenta --}}
                    <div>
                        <label for="Nombre_Cuenta" class="form-label">
                            Nombre de la Cuenta <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fas fa-file-signature text-sm"></i>
                            </span>
                            <input type="text" 
                                   name="Nombre_Cuenta" 
                                   id="Nombre_Cuenta"
                                   class="form-input pl-9"
                                   value="{{ old('Nombre_Cuenta', $cuenta->Nombre_Cuenta) }}" 
                                   required>
                        </div>
                    </div>

                    {{-- Tipo de Cuenta --}}
                    <div>
                        <label for="Tipo" class="form-label">
                            Tipo de Cuenta <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fas fa-tag text-sm"></i>
                            </span>
                            <select name="Tipo" 
                                    id="Tipo" 
                                    class="form-select pl-9" 
                                    required>
                                @foreach($tipos as $tipo)
                                    <option value="{{ $tipo }}" {{ old('Tipo', $cuenta->Tipo) == $tipo ? 'selected' : '' }}>
                                        {{ $tipo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- Indicador visual del tipo seleccionado --}}
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            @php
                                $tipoColores = [
                                    'Activo'          => 'bg-blue-100 text-blue-700',
                                    'Activo (Contra)' => 'bg-amber-100 text-amber-700',
                                    'Pasivo'          => 'bg-orange-100 text-orange-700',
                                    'Patrimonio'      => 'bg-purple-100 text-purple-700',
                                    'Ingreso'         => 'bg-teal-100 text-teal-700',
                                    'Gasto'           => 'bg-gray-100 text-gray-600',
                                    'Costo'           => 'bg-rose-100 text-rose-700',
                                ];
                                $tipoActual = old('Tipo', $cuenta->Tipo);
                            @endphp
                            <span class="text-xs px-2 py-1 rounded-full font-medium {{ $tipoColores[$tipoActual] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $tipoActual }}
                            </span>
                        </div>
                    </div>

                    {{-- Información adicional --}}
                    <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-600">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                            <div>
                                <p class="font-medium text-gray-700 mb-1">Información de la cuenta</p>
                                <ul class="space-y-1 text-xs">
                                    <li>
                                        <span class="text-gray-400">ID:</span> 
                                        <span class="font-mono">{{ $cuenta->Id_Cuenta }}</span>
                                    </li>
                                    <li>
                                        <span class="text-gray-400">Creada:</span> 
                                        {{ $cuenta->created_at ? $cuenta->created_at->format('d/m/Y H:i') : '—' }}
                                    </li>
                                    <li>
                                        <span class="text-gray-400">Última actualización:</span> 
                                        {{ $cuenta->updated_at ? $cuenta->updated_at->format('d/m/Y H:i') : '—' }}
                                    </li>
                                    <li>
                                        <span class="text-gray-400">Movimientos registrados:</span> 
                                        <span class="font-medium">{{ $cuenta->detalles()->count() }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Botones de acción --}}
                <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-200">
                    <button type="submit" 
                            class="btn bg-amber-500 text-white hover:bg-amber-600 focus:ring-amber-400 flex-1">
                        <i class="fas fa-save mr-2"></i> Guardar Cambios
                    </button>
                    <a href="{{ route('contabilidad.plan_cuentas') }}" 
                       class="btn btn-secondary flex-1 text-center">
                        <i class="fas fa-times mr-2"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection