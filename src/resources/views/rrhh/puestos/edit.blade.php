@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <div class="bg-blue-600 p-6">
                <h2 class="text-2xl font-bold text-white">Editar Puesto</h2>
                <p class="text-blue-100 text-sm">Modifica la información del cargo y salario base</p>
            </div>

            <form action="{{ route('rrhh.puestos.update', $puesto->Id_Puesto) }}" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nombre del Cargo</label>
                    <select name="Nombre_Puesto" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all" 
                            required>
                        @php
                            $cargos = ['Jefe de Proyecto', 'Gerente', 'Administrador de Proyectos', 'Coordinador', 'Analista', 'Asistente'];
                        @endphp
                        
                        @foreach($cargos as $cargo)
                            <option value="{{ $cargo }}" {{ (old('Nombre_Puesto', $puesto->Nombre_Puesto) == $cargo) ? 'selected' : '' }}>
                                {{ $cargo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Salario Base Mensual</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-gray-400 font-bold">$</span>
                        <input type="number" step="0.01" name="Salario_Base" value="{{ old('Salario_Base', $puesto->Salario_Base) }}" 
                               class="w-full pl-8 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all" required>
                    </div>
                    @error('Salario_Base') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end space-x-4 pt-4">
                    <a href="{{ route('rrhh.puestos.index') }}" class="text-gray-500 hover:text-gray-700 font-medium">Cancelar</a>
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-md transition-all transform hover:scale-105">
                        Actualizar Puesto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection