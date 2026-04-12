@extends('layouts.app')
@section('title', 'Asignaciones')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Asignaciones</h1>
        <a href="{{ route('asignaciones.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium transition">+ Nueva</a>
    </div>

    @if($asignaciones->isEmpty())
        <div class="bg-gray-50 rounded-lg p-8 text-center">
            <p class="text-gray-500">No hay asignaciones registradas.</p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Empleado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proyecto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Horas</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($asignaciones as $asignacion)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $asignacion->empleado->Nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $asignacion->proyecto->Nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $asignacion->Horas_Asignadas }} hrs</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('asignaciones.show', $asignacion->Id_Asignacion) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Ver</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection