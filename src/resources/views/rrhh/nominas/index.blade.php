@extends('layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        
        {{-- Encabezado Estilo Panel --}}
        <div class="flex justify-between items-center mb-8 bg-white p-6 rounded-lg shadow-sm border-l-4 border-indigo-600">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Historial de Nóminas</h1>
                <p class="text-gray-500 font-medium">Registro de pagos realizados a los empleados</p>
            </div>
            <a href="{{ route('rrhh.nominas.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-md shadow-sm transition-all transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Generar Nuevo Pago
            </a>
        </div>

        {{-- Tabla Estilo Moderno --}}
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Empleado</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Periodo</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Sueldo Bruto</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Deducciones</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Neto a Pagar</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha de Registro</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($nominas as $n)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-indigo-600">
                                {{ $n->empleado->Nombre ?? 'N/A' }} {{ $n->empleado->Apellido ?? '' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                            {{ $n->periodo->Mes ?? 'N/A' }} / {{ $n->periodo->Año ?? '' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-700 font-semibold">
                            ${{ number_format($n->Total_Bruto, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-red-600 font-medium">
                            -${{ number_format($n->Total_Deducciones, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-black text-green-700 bg-green-50">
                            ${{ number_format($n->Neto_Pagar, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            {{ $n->created_at ? $n->created_at->format('d/m/Y') : '---' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">
                            No se han encontrado registros de nóminas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection