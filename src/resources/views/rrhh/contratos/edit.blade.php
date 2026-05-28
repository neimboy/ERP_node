@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        
        {{-- Encabezado --}}
        <div class="flex justify-between items-center mb-8 bg-white p-6 rounded-lg shadow-sm border-l-4 border-indigo-600">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Editar Contrato</h1>
                <p class="text-gray-500 font-medium">Modifica los términos del vínculo laboral</p>
            </div>
            <a href="{{ route('rrhh.contratos.index') }}" class="text-gray-600 hover:text-indigo-600 font-bold transition-colors">
                &larr; Volver al listado
            </a>
        </div>

        <div class="bg-white shadow-xl rounded-lg overflow-hidden p-8">
            <form action="{{ route('rrhh.contratos.update', $contrato->Id_Contrato) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- Empleado (Lectura o Selección) --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Empleado</label>
                        <select name="Id_Empleado" class="w-full p-3 border border-gray-300 rounded-md bg-gray-50 focus:ring-2 focus:ring-indigo-500 outline-none">
                            @foreach($empleados as $emp)
                                <option value="{{ $emp->Id_Empleado }}" {{ $contrato->Id_Empleado == $emp->Id_Empleado ? 'selected' : '' }}>
                                    {{ $emp->Nombre }} {{ $emp->Apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Puesto --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Puesto / Cargo</label>
                        <select name="Id_Puesto" class="w-full p-3 border border-gray-300 rounded-md bg-gray-50 focus:ring-2 focus:ring-indigo-500 outline-none">
                            @foreach($puestos as $pue)
                                <option value="{{ $pue->Id_Puesto }}" {{ $contrato->Id_Puesto == $pue->Id_Puesto ? 'selected' : '' }}>
                                    {{ $pue->Nombre_Puesto }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Fecha Inicio --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Fecha de Inicio</label>
                        <input type="date" name="Fecha_Inicio" value="{{ $contrato->Fecha_Inicio }}" 
                               class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 outline-none" required>
                    </div>

                    {{-- Fecha Fin --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Fecha de Finalización (Opcional)</label>
                        <input type="date" name="Fecha_Fin" value="{{ $contrato->Fecha_Fin }}" 
                               class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 outline-none">
                        <p class="text-xs text-gray-400 mt-1">Dejar vacío si el contrato es indefinido.</p>
                    </div>

                </div>

                <div class="mt-10 flex justify-end gap-4">
                    <a href="{{ route('rrhh.contratos.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-md hover:bg-gray-300 transition-all">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-md shadow-lg hover:bg-indigo-700 transform hover:scale-105 transition-all">
                        Actualizar Contrato
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection