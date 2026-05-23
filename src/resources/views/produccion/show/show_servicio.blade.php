@extends('layouts.app')
@section('title', 'Detalle del Servicio')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('produccion.proyectos.index') }}" class="text-gray-500 hover:text-gray-700">← Atras</a>
            <h1 class="text-2xl font-bold text-gray-800">{{ $proyecto->Nombre }}</h1>
            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Servicio</span>
        </div>
        <a href="{{ route('produccion.proyectos.edit', $proyecto->Id_Proyecto) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Editar</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <p class="text-sm text-gray-500 mb-1">Cliente</p>
                <p class="font-medium text-gray-900">{{ $proyecto->cliente->Nombre ?? 'Sin cliente' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Fecha Inicio</p>
                <p class="font-medium text-gray-900">{{ $proyecto->Fecha_Inicio ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Fecha Fin</p>
                <p class="font-medium text-gray-900">{{ $proyecto->Fecha_Fin ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Estado</p>
                @php
                    $estadoColors = [
                        'Pendiente' => 'bg-yellow-100 text-yellow-800',
                        'En Progreso' => 'bg-blue-100 text-blue-800',
                        'Completado' => 'bg-green-100 text-green-800'
                    ];
                @endphp
                <span class="px-2.5 py-1 rounded-full text-sm font-medium {{ $estadoColors[$proyecto->Estado] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ $proyecto->Estado }}
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Gastos del Servicio</h2>
        @if($proyecto->gastos->isEmpty())
            <p class="text-gray-500">No se han registrado gastos para este servicio.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($proyecto->gastos as $index => $gasto)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $gasto->Descripcion }}</td>
                            <td class="px-6 py-4 text-gray-600">S/ {{ number_format($gasto->Monto, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="2" class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Total Gastos:</td>
                            <td class="px-6 py-3 font-bold text-gray-900">S/ {{ number_format($totalGastos, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>

    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Empleados Asignados ({{ $proyecto->asignaciones->count() }})</h2>
        <a href="{{ route('produccion.asignaciones.create', ['proyecto_id' => $proyecto->Id_Proyecto]) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition">+ Agregar</a>
    </div>

    @if($proyecto->asignaciones->isEmpty())
        <div class="bg-gray-50 rounded-lg p-8 text-center">
            <p class="text-gray-500">No hay empleados asignados.</p>
        </div>
    @else
        @php
            $totalHoras = $proyecto->asignaciones->sum('Horas_Asignadas');
        @endphp
        <div class="bg-indigo-50 rounded-lg p-4 mb-4 flex gap-6">
            <div><span class="text-indigo-600 font-medium">{{ $totalHoras }}</span> <span class="text-gray-600 text-sm">horas asignadas</span></div>
            <div><span class="text-indigo-600 font-medium">{{ $proyecto->asignaciones->count() }}</span> <span class="text-gray-600 text-sm">empleados</span></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Empleado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Horas</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($proyecto->asignaciones as $index => $asignacion)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $asignacion->empleado->Nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $asignacion->Horas_Asignadas }} hrs</td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('produccion.asignaciones.destroy', $asignacion->Id_Asignacion) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium" onclick="return confirm('¿Eliminar esta asignación?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
