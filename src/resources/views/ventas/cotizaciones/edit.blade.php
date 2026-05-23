@extends('layouts.app')

@section('content')

<div class="mb-6">
    <a href="{{ route('cotizaciones.show', $cotizacion) }}" class="text-indigo-600 hover:text-indigo-800">
        <i class="fas fa-arrow-left mr-2"></i> Volver
    </a>
    <h2 class="text-3xl font-bold text-gray-800 mt-2">Editar Cotización #{{ $cotizacion->Id_Cotizacion }}</h2>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('cotizaciones.update', $cotizacion) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="Id_Cliente" class="block text-sm font-medium text-gray-700 mb-1">
                    Cliente
                </label>
                <select id="Id_Cliente" name="Id_Cliente" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->Id_Cliente }}" {{ $cotizacion->Id_Cliente == $cliente->Id_Cliente ? 'selected' : '' }}>
                            {{ $cliente->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="Estado" class="block text-sm font-medium text-gray-700 mb-1">
                    Estado
                </label>
                <select id="Estado" name="Estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="Pendiente" {{ $cotizacion->Estado === 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="Aceptada" {{ $cotizacion->Estado === 'Aceptada' ? 'selected' : '' }}>Aceptada</option>
                    <option value="Rechazada" {{ $cotizacion->Estado === 'Rechazada' ? 'selected' : '' }}>Rechazada</option>
                    <option value="Vencida" {{ $cotizacion->Estado === 'Vencida' ? 'selected' : '' }}>Vencida</option>
                </select>
            </div>
        </div>

        <div class="flex gap-3 justify-end">
            <a href="{{ route('cotizaciones.show', $cotizacion) }}" class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500">
                Cancelar
            </a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>

@endsection
