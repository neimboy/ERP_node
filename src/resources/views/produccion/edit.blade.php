@extends('layouts.app')
@section('title', 'Editar Proyecto')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('produccion.proyectos.show', $proyecto->Id_Proyecto) }}" class="text-gray-500 hover:text-gray-700">← Volver</a>
        <h1 class="text-2xl font-bold text-gray-800">Editar Proyecto</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 max-w-2xl">
        <form action="{{ route('produccion.proyectos.update', $proyecto->Id_Proyecto) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nombre del Proyecto</label>
                <input type="text" name="Nombre" value="{{ $proyecto->Nombre }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Cliente</label>
                <select name="Id_Cliente" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->Id_Cliente }}" {{ $cliente->Id_Cliente == $proyecto->Id_Cliente ? 'selected' : '' }}>
                            {{ $cliente->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Fecha de Inicio</label>
                    <input type="date" name="Fecha_Inicio" value="{{ $proyecto->Fecha_Inicio }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Fecha Fin</label>
                    <input type="date" name="Fecha_Fin" value="{{ $proyecto->Fecha_Fin }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Estado</label>
                <select name="Estado" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="Pendiente" {{ $proyecto->Estado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="En Progreso" {{ $proyecto->Estado == 'En Progreso' ? 'selected' : '' }}>En Progreso</option>
                    <option value="Completado" {{ $proyecto->Estado == 'Completado' ? 'selected' : '' }}>Completado</option>
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium transition">Actualizar</button>
            </div>
        </form>

        <div class="px-6 pb-6">
            <form action="{{ route('produccion.proyectos.destroy', $proyecto->Id_Proyecto) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium" onclick="return confirm('¿Estás seguro de eliminar este proyecto?')">Eliminar proyecto</button>
            </form>
        </div>
    </div>
</div>
@endsection
