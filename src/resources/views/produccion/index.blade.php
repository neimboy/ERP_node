@extends('layouts.app')
@section('title', 'Proyectos')

@section('content')
<div class="container">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Proyectos</h1>
        <a href="{{ route('proyectos.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Nuevo Proyecto</a>
    </div>

    @if($proyectos->isEmpty())
        <p>No hay proyectos registrados.</p>
    @else
        <table class="min-w-full bg-white border">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-4 py-2 border">Nombre</th>
                    <th class="px-4 py-2 border">Cliente</th>
                    <th class="px-4 py-2 border">Fecha Inicio</th>
                    <th class="px-4 py-2 border">Fecha Fin</th>
                    <th class="px-4 py-2 border">Estado</th>
                    <th class="px-4 py-2 border">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proyectos as $proyecto)
                <tr class="border">
                    <td class="px-4 py-2">{{ $proyecto->Nombre }}</td>
                    <td class="px-4 py-2">{{ $proyecto->cliente->Nombre ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $proyecto->Fecha_Inicio }}</td>
                    <td class="px-4 py-2">{{ $proyecto->Fecha_Fin }}</td>
                    <td class="px-4 py-2">{{ $proyecto->Estado }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('proyectos.show', $proyecto->Id_Proyecto) }}" class="text-blue-500">Ver</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
