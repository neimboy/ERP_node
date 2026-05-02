@extends('layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        
        <div class="mb-8 bg-white p-6 rounded-lg shadow-sm border-l-4 border-indigo-600">
            <h1 class="text-3xl font-bold text-gray-800">Registrar Nuevo Contrato</h1>
            <p class="text-gray-500 font-medium">Complete la información para vincular un empleado a un puesto</p>
        </div>

        <div class="bg-white shadow-xl rounded-lg p-8">
            <form action="{{ route('rrhh.contratos.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Empleado --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Seleccionar Empleado</label>
                        <select name="Id_Empleado" class="w-full p-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="">Seleccione un empleado...</option>
                            @foreach($empleados as $e)
                                <option value="{{ $e->Id_Empleado }}">{{ $e->Nombre }} {{ $e->Apellido }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Puesto --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Puesto de Trabajo</label>
                        <select name="Id_Puesto" class="w-full p-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="">Seleccione un puesto...</option>
                            @foreach($puestos as $p)
                                <option value="{{ $p->Id_Puesto }}">{{ $p->Nombre_Puesto }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Fecha Inicio --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Fecha de Inicio</label>
                        <input type="date" name="Fecha_Inicio" class="w-full p-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>

                    {{-- Fecha Fin --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Fecha de Finalización (Opcional)</label>
                        <input type="date" name="Fecha_Fin" class="w-full p-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('rrhh.contratos.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 font-bold hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-md shadow-sm hover:bg-indigo-700 transition-colors">
                        Guardar Contrato
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection