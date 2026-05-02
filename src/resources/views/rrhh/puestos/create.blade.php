@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl border-t-4 border-blue-600">
        <div class="p-8">
            <div class="flex items-center mb-6">
                <a href="{{ route('rrhh.puestos.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h2 class="text-2xl font-bold text-gray-800">Crear Nuevo Puesto</h2>
            </div>

            <form action="{{ route('rrhh.puestos.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-6">
                    {{-- Nombre del Puesto --}}
                    <<div>
                        <label class="block text-sm font-bold text-gray-700">Nombre del Cargo</label>
                        <select name="Nombre_Puesto" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('Nombre_Puesto') border-red-500 @enderror">
                            
                            <option value="" disabled {{ old('Nombre_Puesto') ? '' : 'selected' }}>-- Seleccione un cargo --</option>
                            
                            @php
                                $cargos = [
                                    'Jefe de Proyecto', 
                                    'Gerente', 
                                    'Administrador de Proyectos', 
                                    'Coordinador', 
                                    'Analista', 
                                    'Asistente'
                                ];
                            @endphp

                            @foreach($cargos as $cargo)
                                <option value="{{ $cargo }}" {{ old('Nombre_Puesto') == $cargo ? 'selected' : '' }}>
                                    {{ $cargo }}
                                </option>
                            @endforeach
                        </select>

                        @error('Nombre_Puesto')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Salario Base --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700">Salario Base ($)</label>
                        <input type="number" step="0.01" name="Salario_Base" value="{{ old('Salario_Base') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('Salario_Base') border-red-500 @enderror" 
                               placeholder="0.00">
                        @error('Salario_Base')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                            Guardar Puesto
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection