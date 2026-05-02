@extends('layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        
        {{-- Encabezado Estilo Panel (Igual a Nóminas) --}}
        <div class="flex justify-between items-center mb-8 bg-white p-6 rounded-lg shadow-sm border-l-4 border-indigo-600">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Listado de Contratos</h1>
                <p class="text-gray-500 font-medium">Gestión de vínculos laborales y periodos de vigencia</p>
            </div>
            <a href="{{ route('rrhh.contratos.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-md shadow-sm transition-all transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Contrato
            </a>
        </div>

        {{-- Alerta de Éxito --}}
        @if(session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                <p class="font-bold">¡Logrado!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- Tabla --}}
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Empleado</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Puesto</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha Inicio</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha Fin</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($contratos as $c)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-indigo-600">
                                {{ $c->empleado->Nombre ?? 'N/A' }} {{ $c->empleado->Apellido ?? '' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                            {{ $c->puesto->Nombre_Puesto ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700">
                            {{ $c->Fecha_Inicio }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700">
                            {{ $c->Fecha_Fin ?? 'Indefinido' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                             <a href="{{ route('rrhh.contratos.edit', $c->Id_Contrato) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                        </td>
                        
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">
                            No se han encontrado registros de contratos.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection